import AssetService from '../services/AssetService';

const FETCHING_ASSETS = "FETCHING_ASSETS";
const FETCHING_ASSETS_SUCCESS = "FETCHING_ASSETS_SUCCESS";
const FETCHING_ASSETS_ERROR = "FETCHING_ASSETS_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    assets: [],
  },
  mutations: {
    [FETCHING_ASSETS](state) {
      state.isLoading = true;
      state.error = null;
      state.assets = [];
    },
    [FETCHING_ASSETS_SUCCESS](state, assets) {
      state.isLoading = false;
      state.error = null;
      state.assets = assets;
    },
    [FETCHING_ASSETS_ERROR](state, error) {
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
    hasAssets(state) {
      return state.assets.length > 0;
    },
    assets(state) {
      return state.assets;
    },
    getById: (state) => (id) => {
      return state.assets.find(item => item.id === id);
    },
  },
  actions: {
    async getAll({ commit, dispatch }) {
      commit(FETCHING_ASSETS);
      try {
        const response = await AssetService.getAll();
        commit(FETCHING_ASSETS_SUCCESS, response.data.content);

        return response.data.content;
      } catch (error) {
        commit(FETCHING_ASSETS_ERROR, error);

        if (error.response.status === 401) {
          dispatch('security/logoff', null, { root: true });
        }

        return null;
      }
    }
  }
};