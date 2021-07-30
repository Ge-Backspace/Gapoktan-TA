const middleware = {}

middleware['admin'] = require('..\\middleware\\admin.js')
middleware['admin'] = middleware['admin'].default || middleware['admin']

middleware['lapor'] = require('..\\middleware\\lapor.js')
middleware['lapor'] = middleware['lapor'].default || middleware['lapor']

middleware['superadmin'] = require('..\\middleware\\superadmin.js')
middleware['superadmin'] = middleware['superadmin'].default || middleware['superadmin']

middleware['user'] = require('..\\middleware\\user.js')
middleware['user'] = middleware['user'].default || middleware['user']

export default middleware
