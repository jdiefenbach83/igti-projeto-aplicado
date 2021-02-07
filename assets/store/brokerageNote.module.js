import BrokerageNoteService from '../services/BrokerageNoteService';

const FETCHING_BROKERAGE_NOTES = "FETCHING_BROKERAGE_NOTES";
const FETCHING_BROKERAGE_NOTES_SUCCESS = "FETCHING_BROKERAGE_NOTES_SUCCESS";
const FETCHING_BROKERAGE_NOTES_ERROR = "FETCHING_BROKERAGE_NOTES_ERROR";

const ADDING_BROKERAGE_NOTE = "ADDING_BROKERAGE_NOTE";
const ADDING_BROKERAGE_NOTE_SUCCESS = "ADDING_BROKERAGE_NOTE_SUCCESS";
const ADDING_BROKERAGE_NOTE_ERROR = "ADDING_BROKERAGE_NOTE_ERROR";

const EDITING_BROKERAGE_NOTE = "EDITING_BROKERAGE_NOTE";
const EDITING_BROKERAGE_NOTE_SUCCESS = "EDITING_BROKERAGE_NOTE_SUCCESS";
const EDITING_BROKERAGE_NOTE_ERROR = "EDITING_BROKERAGE_NOTE_ERROR";

const REMOVING_BROKERAGE_NOTE = "REMOVING_BROKERAGE_NOTE";
const REMOVING_BROKERAGE_NOTE_SUCCESS = "REMOVING_BROKERAGE_NOTE_SUCCESS";
const REMOVING_BROKERAGE_NOTE_ERROR = "REMOVING_BROKERAGE_NOTE_ERROR";

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
    [EDITING_BROKERAGE_NOTE](state) {
      state.isLoading = true;
      state.error = null;
    },
    [EDITING_BROKERAGE_NOTE_SUCCESS](state, brokerageNote) {
      state.isLoading = false;
      state.error = null;
      
      const brokerageNotes = state.brokerageNotes = state.brokerageNotes.filter(item => item.id !== brokerageNote.id);
      
      state.brokerageNotes = [...brokerageNotes, brokerageNote];
    },
    [EDITING_BROKERAGE_NOTE_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
    },
    [REMOVING_BROKERAGE_NOTE](state) {
      state.isLoading = true;
      state.error = null;
    },
    [REMOVING_BROKERAGE_NOTE_SUCCESS](state, brokerageNote) {
      state.isLoading = false;
      state.error = null;
      state.brokerageNotes = state.brokerageNotes.filter(item => item.id !== brokerageNote.id);
    },
    [REMOVING_BROKERAGE_NOTE_ERROR](state, error) {
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
    },
    getById: (state) => (id) => {
      return state.brokerageNotes.find(item => item.id === id);
    },
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
        delete message.id;

        const response = await BrokerageNoteService.add(message);
        commit(ADDING_BROKERAGE_NOTE_SUCCESS, response.content);

        return response.data;
      } catch (error) {
        commit(ADDING_BROKERAGE_NOTE_ERROR, error);

        return null;
      }
    },
    async edit({ commit }, message) {
      commit(EDITING_BROKERAGE_NOTE);
      try {
        const id = message.id;
        delete message.id;

        const response = await BrokerageNoteService.edit(id, message);
        commit(EDITING_BROKERAGE_NOTE_SUCCESS, response.content);

        return response.data;
      } catch (error) {
        commit(EDITING_BROKERAGE_NOTE_ERROR, error);

        return null;
      }
    },
    async remove({ commit }, message) {
      commit(REMOVING_BROKERAGE_NOTE);
      try {
        const response = await BrokerageNoteService.remove(message);
        commit(REMOVING_BROKERAGE_NOTE_SUCCESS, message);

        return response.data;
      } catch (error) {
        commit(REMOVING_BROKERAGE_NOTE_ERROR, error);

        return null;
      }
    },
  }
};