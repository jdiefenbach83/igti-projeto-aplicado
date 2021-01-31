const gateway = require('@/api/httpClient');

async function getAll() {
  const { data } = await gateway.httpClient().get('/brokerageNotes');

  return data;
}

async function add(brokerageNote) {
  const { data } = await gateway.httpClient().post('/brokerageNotes', JSON.stringify(brokerageNote));

  return data;
}

async function remove(brokerageNote) {
  const { data } = await gateway.httpClient().delete(`/brokerageNotes/${brokerageNote.id}`);

  return data;
}

module.exports = {
  getAll,
  add,
  remove,
};