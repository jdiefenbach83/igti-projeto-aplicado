import PositionService from '../services/PositionService';

const FETCHING_POSITIONS = "FETCHING_POSITIONS";
const FETCHING_POSITIONS_SUCCESS = "FETCHING_POSITIONS_SUCCESS";
const FETCHING_POSITIONS_ERROR = "FETCHING_POSITIONS_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    positions: [],
  },
  mutations: {
    [FETCHING_POSITIONS](state) {
      state.isLoading = true;
      state.error = null;
      state.positions = [];
    },
    [FETCHING_POSITIONS_SUCCESS](state, positions) {
      state.isLoading = false;
      state.error = null;
      state.positions = positions;
    },
    [FETCHING_POSITIONS_ERROR](state, error) {
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
    hasPositions(state) {
      return state.positions.length > 0;
    },
    positions(state) {
      return state.positions;
    },
    getById: (state) => (id) => {
      return state.positions.find(item => item.id === id);
    },
  },
  actions: {
    async getAll({ commit, dispatch }) {
      commit(FETCHING_POSITIONS);
      try {
        const response = await PositionService.getAll();
        commit(FETCHING_POSITIONS_SUCCESS, response.data.content);

        return response.data.content;
      } catch (error) {
        commit(FETCHING_POSITIONS_ERROR, error);

        if (error.response.status === 401) {
          dispatch('security/logoff', null, { root: true });
        }

        return null;
      }
    }
  }
};