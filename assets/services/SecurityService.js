const gateway = require('@/api/httpClient');

const login = async (username, password) => {
  const payload = {
    username,
    password
  };

  const { data } = await gateway.httpClient().post('/login', payload);

  return data;
}

module.exports = {
  login,
};