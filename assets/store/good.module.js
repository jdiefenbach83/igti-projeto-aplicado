import GoodService from '../services/GoodService';

const FETCHING_GOODS = "FETCHING_GOODS";
const FETCHING_GOODS_SUCCESS = "FETCHING_GOODS_SUCCESS";
const FETCHING_GOODS_ERROR = "FETCHING_GOODS_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    goods: [],
  },
  mutations: {
    [FETCHING_GOODS](state) {
      state.isLoading = true;
      state.error = null;
      state.goods = [];
    },
    [FETCHING_GOODS_SUCCESS](state, goods) {
      state.isLoading = false;
      state.error = null;
      state.goods = goods;
    },
    [FETCHING_GOODS_ERROR](state, error) {
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
    hasGoods(state) {
      return state.goods.length > 0;
    },
    goods(state) {
      return state.goods;
    },
    getById: (state) => (id) => {
      return state.goods.find(item => item.id === id);
    },
  },
  actions: {
    async getAll({ commit }) {
      commit(FETCHING_GOODS);
      try {
        const response = await GoodService.getAll();
        commit(FETCHING_GOODS_SUCCESS, response.content);

        return response.content;
      } catch (error) {
        commit(FETCHING_GOODS_ERROR, error);

        return null;
      }
    }
  }
};