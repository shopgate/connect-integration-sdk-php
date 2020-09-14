const request = require('request-promise-native')

const esIndex = require('./locationIndex.json')
const elasticUrl = 'http://elasticsearch:9200'

async function prepareEs () {
  await request({
    url: `${elasticUrl}/development_merchant_1_location`,
    strictSSL: false,
    auth: { user: 'elastic', pass: 'omni' },
    method: 'PUT',
    body: esIndex,
    json: true
  })
  
  await request({
    url: `${elasticUrl}/development_merchant_2_location`,
    strictSSL: false,
    auth: { user: 'elastic', pass: 'omni' },
    method: 'PUT',
    body: esIndex,
    json: true
  })  
}

prepareEs()
  .then(() => {
    console.log('Elasticseach initialized')
    process.exit(0)
  })
  .catch(err => {
    console.error(err, 'Failed initializing elasticseach')
    process.exit(1)
  })

