import CompanyService from '../services/CompanyService';

const FETCHING_COMPANIES = "FETCHING_COMPANIES";
const FETCHING_COMPANIES_SUCCESS = "FETCHING_COMPANIES_SUCCESS";
const FETCHING_COMPANIES_ERROR = "FETCHING_COMPANIES_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    companies: [],
  },
  mutations: {
    [FETCHING_COMPANIES](state) {
      state.isLoading = true;
      state.error = null;
      state.companies = [];
    },
    [FETCHING_COMPANIES_SUCCESS](state, companies) {
      state.isLoading = false;
      state.error = null;
      state.companies = companies;
    },
    [FETCHING_COMPANIES_ERROR](state, error) {
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
    hasCompanies(state) {
      return state.companies.length > 0;
    },
    companies(state) {
      return state.companies;
    },
    getById: (state) => (id) => {
      return state.companies.find(item => item.id === id);
    },
  },
  actions: {
    async getAll({ commit, dispatch }) {
      commit(FETCHING_COMPANIES);
      try {
        const response = await CompanyService.getAll();
        commit(FETCHING_COMPANIES_SUCCESS, response.data.content);

        return response.data.content;
      } catch (error) {
        commit(FETCHING_COMPANIES_ERROR, error);

        if (error.response.status === 401) {
          dispatch('security/logoff', null, { root: true });
        }

        return null;
      }
    }
  }
};