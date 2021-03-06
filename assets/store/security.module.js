import SecurityService from '../services/SecurityService';

const AUTHENTICATING = 'AUTHENTICATING';
const AUTHENTICATING_SUCCESS = 'AUTHENTICATING_SUCCESS';
const AUTHENTICATING_ERROR = 'AUTHENTICATING_ERROR';

const LOGGING_OFF = 'LOGGING_OFF';
const LOGOFF_SUCCESS = 'LOGOFF_SUCCESS';
const LOGOFF_ERROR = 'LOGOFF_ERROR';

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    isAuthenticated: false,
    accessToken: null,
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
    isAuthenticated(state) {
      return state.isAuthenticated;
    },
    getAccessToken() {
      return state.accessToken;
    }
  },
  mutations: {
    [AUTHENTICATING](state) {
      state.isLoading = true;
      state.error = null;
      state.isAuthenticated = false;
    },
    [AUTHENTICATING_SUCCESS](state, accessToken) {
      state.isLoading = false;
      state.error = null;
      state.isAuthenticated = true;
      state.accessToken = accessToken;

      localStorage.setItem('token', accessToken);
    },
    [AUTHENTICATING_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.isAuthenticated = false;

      localStorage.removeItem('token')
    },
    [LOGGING_OFF](state) {
      state.isLoading = true;
      state.error = null;
      state.isAuthenticated = false;
    },
    [LOGOFF_SUCCESS](state) {
      state.isLoading = false;
      state.error = null;
      state.isAuthenticated = false;
      state.accessToken = null;

      localStorage.removeItem('token')
    },
    [LOGOFF_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.isAuthenticated = false;

      localStorage.removeItem('token')
    }
  },
  actions: {
    async login({ commit }, payload) {
      commit(AUTHENTICATING);

      try {
        const response = await SecurityService.login(payload.email, payload.password);

        const isSuccessful = response.success;

        if (isSuccessful) {
          commit(AUTHENTICATING_SUCCESS, response.content.access_token);
        } else {
          commit(AUTHENTICATING_ERROR, response.content);
        }

        return response.content;
      } catch (error) {
        commit(AUTHENTICATING_ERROR, error);

        return null;
      }
    },
    restoreLogin({ commit }) {
      commit(AUTHENTICATING);

      const token = localStorage.getItem('token');

      if (!!token === true && !!this.state.isAuthenticated === false) {
        commit(AUTHENTICATING_SUCCESS, token);
      }
    },
    logoff({ commit }) {
      commit(LOGGING_OFF);
      commit(LOGOFF_SUCCESS);
    }
  }
}