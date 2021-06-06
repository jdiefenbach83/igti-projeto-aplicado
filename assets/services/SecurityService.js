const gateway = require('@/api/httpClient');

const login = async (login, password) => {
  const payload = {
    email: login,
    password: password
  };

  const { data } = await gateway.httpClient().post('/login', payload);

  return data;
}

module.exports = {
  login,
};