import BrokerageNoteService from '../services/BrokerageNoteService';

const FETCHING_BROKERAGE_NOTES = "FETCHING_BROKERAGE_NOTES";
const FETCHING_BROKERAGE_NOTES_SUCCESS = "FETCHING_BROKERAGE_NOTES_SUCCESS";
const FETCHING_BROKERAGE_NOTES_ERROR = "FETCHING_BROKERAGE_NOTES_ERROR";

const FETCHING_BROKERAGE_NOTE = "FETCHING_BROKERAGE_NOTE";
const FETCHING_BROKERAGE_NOTE_SUCCESS = "FETCHING_BROKERAGE_NOTE_SUCCESS";
const FETCHING_BROKERAGE_NOTE_ERROR = "FETCHING_BROKERAGE_NOTE_ERROR";

const ADDING_BROKERAGE_NOTE = "ADDING_BROKERAGE_NOTE";
const ADDING_BROKERAGE_NOTE_SUCCESS = "ADDING_BROKERAGE_NOTE_SUCCESS";
const ADDING_BROKERAGE_NOTE_ERROR = "ADDING_BROKERAGE_NOTE_ERROR";

const EDITING_BROKERAGE_NOTE = "EDITING_BROKERAGE_NOTE";
const EDITING_BROKERAGE_NOTE_SUCCESS = "EDITING_BROKERAGE_NOTE_SUCCESS";
const EDITING_BROKERAGE_NOTE_ERROR = "EDITING_BROKERAGE_NOTE_ERROR";

const REMOVING_BROKERAGE_NOTE = "REMOVING_BROKERAGE_NOTE";
const REMOVING_BROKERAGE_NOTE_SUCCESS = "REMOVING_BROKERAGE_NOTE_SUCCESS";
const REMOVING_BROKERAGE_NOTE_ERROR = "REMOVING_BROKERAGE_NOTE_ERROR";

const ADDING_OPERATION = "ADDING_OPERATION";
const ADDING_OPERATION_SUCCESS = "ADDING_OPERATION_SUCCESS";
const ADDING_OPERATION_ERROR = "ADDING_OPERATION_ERROR";

const EDITING_OPERATION = "EDITING_OPERATION";
const EDITING_OPERATION_SUCCESS = "EDITING_OPERATION_SUCCESS";
const EDITING_OPERATION_ERROR = "EDITING_OPERATION_ERROR";

const REMOVING_OPERATION = "REMOVING_OPERATION";
const REMOVING_OPERATION_SUCCESS = "REMOVING_OPERATION_SUCCESS";
const REMOVING_OPERATION_ERROR = "REMOVING_OPERATION_ERROR";

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
    [FETCHING_BROKERAGE_NOTE](state) {
      state.isLoading = true;
      state.error = null;
    },
    [FETCHING_BROKERAGE_NOTE_SUCCESS](state, brokerageNote) {
      state.isLoading = false;
      state.error = null;

      const brokerageNotes = state.brokerageNotes.filter(item => item.id !== brokerageNote.id);
      state.brokerageNotes = [...brokerageNotes, brokerageNote];
    },
    [FETCHING_BROKERAGE_NOTE_ERROR](state, error) {
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
      
      const brokerageNotes = state.brokerageNotes.filter(item => item.id !== brokerageNote.id);
      
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
    [ADDING_OPERATION](state) {
      state.isLoading = true;
      state.error = null;
    },
    [ADDING_OPERATION_SUCCESS](state) {
      state.isLoading = false;
      state.error = null;
    },
    [ADDING_OPERATION_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
    },
    [EDITING_OPERATION](state) {
      state.isLoading = true;
      state.error = null;
    },
    [EDITING_OPERATION_SUCCESS](state) {
      state.isLoading = false;
      state.error = null;
    },
    [EDITING_OPERATION_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
    },
    [REMOVING_OPERATION](state) {
      state.isLoading = true;
      state.error = null;
    },
    [REMOVING_OPERATION_SUCCESS](state) {
      state.isLoading = false;
      state.error = null;
    },
    [REMOVING_OPERATION_ERROR](state, error) {
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
    getOperationById: (state) => (brokerage_note_id, operation_id) => {
      const brokerageNote = state.brokerageNotes.find(item => item.id === brokerage_note_id);

      return brokerageNote.operations.find(item => item.id === operation_id);
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
    async getById({ commit }, message) {
      commit(FETCHING_BROKERAGE_NOTE);
      try {
        const id = message.id;

        const response = await BrokerageNoteService.getById(id);
        commit(FETCHING_BROKERAGE_NOTE_SUCCESS, response.content);

        return response.content;
      } catch (error) {
        commit(FETCHING_BROKERAGE_NOTE_ERROR, error);

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

        await dispatch('position/getAll');

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

        await dispatch('position/getAll');

        return response.data;
      } catch (error) {
        commit(REMOVING_BROKERAGE_NOTE_ERROR, error);

        return null;
      }
    },
    async addOperation({ commit, dispatch }, message) {
      commit(ADDING_OPERATION);
      try {
        delete message.id;

        const response = await BrokerageNoteService.addOperation(message);
        commit(ADDING_OPERATION_SUCCESS);

        const payload = { id: message.brokerage_note_id };
        await dispatch('getById', payload);
        await dispatch('position/getAll');

        return response.data;
      } catch (error) {
        commit(ADDING_OPERATION_ERROR, error);

        return null;
      }
    },
    async editOperation({ commit, dispatch }, message) {
      commit(EDITING_OPERATION);
      try {
        delete message.id;

        const response = await BrokerageNoteService.editOperation(message);
        commit(EDITING_OPERATION_SUCCESS);

        const payload = { id: message.brokerage_note_id };
        await dispatch('getById', payload);
        await dispatch('position/getAll');

        return response.data;
      } catch (error) {
        commit(EDITING_OPERATION_ERROR, error);

        return null;
      }
    },
    async removeOperation({ commit, dispatch }, message) {
      commit(REMOVING_OPERATION);
      try {

        const response = await BrokerageNoteService.removeOperation(message);
        commit(REMOVING_OPERATION_SUCCESS);

        const payload = { id: message.brokerage_note_id };
        await dispatch('getById', payload);
        await dispatch('position/getAll');

        return response.data;
      } catch (error) {
        commit(REMOVING_OPERATION_ERROR, error);

        return null;
      }
    },
  }
};