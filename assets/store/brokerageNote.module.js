import BrokerageNoteService from '../services/BrokerageNoteService';

const FETCHING_BROKERAGE_NOTES = "FETCHING_BROKERAGE_NOTES";
const FETCHING_BROKERAGE_NOTES_SUCCESS = "FETCHING_BROKERAGE_NOTES_SUCCESS";
const FETCHING_BROKERAGE_NOTES_ERROR = "FETCHING_BROKERAGE_NOTES_ERROR";

const ADDING_BROKERAGE_NOTE = "ADDING_BROKERAGE_NOTE";
const ADDING_BROKERAGE_NOTE_SUCCESS = "ADDING_BROKERAGE_NOTE_SUCCESS";
const ADDING_BROKERAGE_NOTE_ERROR = "ADDING_BROKERAGE_NOTE_ERROR";

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
    },
    [ADDING_BROKERAGE_NOTE](state) {
      state.isLoading = true;
      state.error = null;
    },
    [ADDING_BROKERAGE_NOTE_SUCCESS](state, brokerageNote) {
      state.isLoading = false;
      state.error = null;
      state.brokerageNotes = [...state.brokerageNotes, brokerageNote];
    },
    [ADDING_BROKERAGE_NOTE_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
    },
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
    },
    async add({ commit }, message) {
      commit(ADDING_BROKERAGE_NOTE);
      try {
        let response = await BrokerageNoteService.add(message);
        commit(ADDING_BROKERAGE_NOTE_SUCCESS, response.content);

        return response.data;
      } catch (error) {
        commit(ADDING_BROKERAGE_NOTE_ERROR, error);

        return null;
      }
    },
  }
};