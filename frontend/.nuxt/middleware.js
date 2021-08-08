const middleware = {}

middleware['admin'] = require('..\\middleware\\admin.js')
middleware['admin'] = middleware['admin'].default || middleware['admin']

middleware['costumer'] = require('..\\middleware\\costumer.js')
middleware['costumer'] = middleware['costumer'].default || middleware['costumer']

middleware['gapoktan'] = require('..\\middleware\\gapoktan.js')
middleware['gapoktan'] = middleware['gapoktan'].default || middleware['gapoktan']

middleware['lapor'] = require('..\\middleware\\lapor.js')
middleware['lapor'] = middleware['lapor'].default || middleware['lapor']

middleware['poktan'] = require('..\\middleware\\poktan.js')
middleware['poktan'] = middleware['poktan'].default || middleware['poktan']

middleware['superadmin'] = require('..\\middleware\\superadmin.js')
middleware['superadmin'] = middleware['superadmin'].default || middleware['superadmin']

middleware['user'] = require('..\\middleware\\user.js')
middleware['user'] = middleware['user'].default || middleware['user']

export default middleware
