const axios = require('axios');

function httpClient() {
  return axios.create({
    baseURL: '/api',
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
    },
  });
}

module.exports = {
  httpClient,
};