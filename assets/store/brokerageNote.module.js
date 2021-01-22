import BrokerageNoteService from '../services/BrokerageNoteService';

const FETCHING_BROKERAGE_NOTES = "FETCHING_BROKERAGE_NOTES";
const FETCHING_BROKERAGE_NOTES_SUCCESS = "FETCHING_BROKERAGE_NOTES_SUCCESS";
const FETCHING_BROKERAGE_NOTES_ERROR = "FETCHING_BROKERAGE_NOTES_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    brokerageNotes: [],
  },
  mutations: {
    [FETCHING_BROKERAGE_NOTES](state) {
      state.isLoading = true;
      state.error = null;
      state.brokerageNotes = [];
    },
    [FETCHING_BROKERAGE_NOTES_SUCCESS](state, brokers) {
      state.isLoading = false;
      state.error = null;
      state.brokerageNotes = brokers;
    },
    [FETCHING_BROKERAGE_NOTES_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.brokerageNotes = [];
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
    hasBrokerageNotes(state) {
      return state.brokerageNotes.length > 0;
    },
    brokerageNotes(state) {
      return state.brokerageNotes;
    }
  },
  actions: {
    async getAll({ commit }) {
      commit(FETCHING_BROKERAGE_NOTES);
      try {
        const response = await BrokerageNoteService.getAll();
        commit(FETCHING_BROKERAGE_NOTES_SUCCESS, response.content);

        return response.content;
      } catch (error) {
        commit(FETCHING_BROKERAGE_NOTES_ERROR, error);

        return null;
      }
    }
  }
};