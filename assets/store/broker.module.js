import BrokerService from '../services/BrokerService';

const FETCHING_BROKERS = "FETCHING_BROKERS";
const FETCHING_BROKERS_SUCCESS = "FETCHING_BROKERS_SUCCESS";
const FETCHING_BROKERS_ERROR = "FETCHING_BROKERS_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    brokers: [],
  },
  mutations: {
    [FETCHING_BROKERS](state) {
      state.isLoading = true;
      state.error = null;
      state.brokers = [];
    },
    [FETCHING_BROKERS_SUCCESS](state, brokers) {
      state.isLoading = false;
      state.error = null;
      state.brokers = brokers;
    },
    [FETCHING_BROKERS_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
    }
  },
  getters: {
    isLoading(state) {
      return state.isLoading;
    },
    hasError(state) {
      return state.error !== null;
    },
    error(state) {
      return state.error;
    },
    hasBrokers(state) {
      return state.brokers.length > 0;
    },
    brokers(state) {
      return state.brokers;
    },
    getById: (state) => (id) => {
      return state.brokers.find(item => item.id === id);
    },
  },
  actions: {
    async getAll({ commit, dispatch  }) {
      commit(FETCHING_BROKERS);
      try {
        const response = await BrokerService.getAll();

        commit(FETCHING_BROKERS_SUCCESS, response.data.content);

        return response.data.content;
      } catch (error) {
        commit(FETCHING_BROKERS_ERROR, error);

        if (error.response.status === 401) {
          dispatch('security/logoff', null, { root: true });
        }

        return null;
      }
    }
  }
};