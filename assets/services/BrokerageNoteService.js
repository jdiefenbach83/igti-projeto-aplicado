const gateway = require('@/api/httpClient');

async function getAll() {
  const { data } = await gateway.httpClient().get('/brokerageNotes');

  return data;
}

async function getById(id) {
  const { data } = await gateway.httpClient().get(`/brokerageNotes/${id}`);

  return data;
}

async function add(brokerageNote) {
  const { data } = await gateway.httpClient().post('/brokerageNotes', JSON.stringify(brokerageNote));

  return data;
}

async function edit(id, brokerageNote) {
  const { data } = await gateway.httpClient().put(`/brokerageNotes/${id}`, JSON.stringify(brokerageNote));

  return data;
}

async function remove(brokerageNote) {
  const { data } = await gateway.httpClient().delete(`/brokerageNotes/${brokerageNote.id}`);

  return data;
}

async function addOperation(operation) {
  const { data } = await gateway.httpClient().post(`/brokerageNotes/${operation.brokerage_note_id}/operations`, JSON.stringify(operation));

  return data;
}

async function editOperation(operation) {
  const { data } = await gateway.httpClient().put(`/brokerageNotes/${operation.brokerage_note_id}/operations/${operation.line}`, JSON.stringify(operation));

  return data;
}

async function removeOperation(operation) {
  const { data } = await gateway.httpClient().delete(`/brokerageNotes/${operation.brokerage_note_id}/operations/${operation.line}`);

  return data;
}

module.exports = {
  getAll,
  getById,
  add,
  edit,
  remove,
  addOperation,
  editOperation,
  removeOperation,
};