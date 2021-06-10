const axios = require('axios');

const httpClient = () => {
  const token = localStorage.getItem('token') ?? null;

  const headers = {
    Accept: 'application/json',
    'Content-Type': 'application/json',
    Authorization: token,
  };

  return axios.create({
    baseURL: '/api',
    headers,
  });
}

module.exports = {
  httpClient,
};