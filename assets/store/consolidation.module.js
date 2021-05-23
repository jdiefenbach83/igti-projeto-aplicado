import ConsolidationService from '../services/ConsolidationService';

const FETCHING_CONSOLIDATIONS = "FETCHING_CONSOLIDATIONS";
const FETCHING_CONSOLIDATIONS_SUCCESS = "FETCHING_CONSOLIDATIONS_SUCCESS";
const FETCHING_CONSOLIDATIONS_ERROR = "FETCHING_CONSOLIDATIONS_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    consolidations: [],
  },
  mutations: {
    [FETCHING_CONSOLIDATIONS](state) {
      state.isLoading = true;
      state.error = null;
      state.consolidations = [];
    },
    [FETCHING_CONSOLIDATIONS_SUCCESS](state, consolidations) {
      state.isLoading = false;
      state.error = null;
      state.consolidations = consolidations;
    },
    [FETCHING_CONSOLIDATIONS_ERROR](state, error) {
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
    hasConsolidations(state) {
      return state.consolidations.length > 0;
    },
    consolidations(state) {
      return state.consolidations;
    },
    getById: (state) => (id) => {
      return state.consolidations.find(item => item.id === id);
    },
  },
  actions: {
    async getAll({ commit }) {
      commit(FETCHING_CONSOLIDATIONS);
      try {
        const response = await ConsolidationService.getAll();
        commit(FETCHING_CONSOLIDATIONS_SUCCESS, response.content);

        return response.content;
      } catch (error) {
        commit(FETCHING_CONSOLIDATIONS_ERROR, error);

        return null;
      }
    }
  }
};