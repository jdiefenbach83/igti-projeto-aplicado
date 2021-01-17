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
      state.brokers = [];
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
    }
  },
  actions: {
    async getAll({ commit }) {
      commit(FETCHING_BROKERS);
      try {
        const response = await BrokerService.getAll();
        commit(FETCHING_BROKERS_SUCCESS, response.content);

        return response.content;
      } catch (error) {
        commit(FETCHING_BROKERS_ERROR, error);

        return null;
      }
    }
  }
};