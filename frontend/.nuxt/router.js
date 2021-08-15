import Vue from 'vue'
import Router from 'vue-router'
import { normalizeURL, decode } from '@nuxt/ufo'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _2fd3d565 = () => interopDefault(import('..\\pages\\admin\\index.vue' /* webpackChunkName: "pages/admin/index" */))
const _a17ae0a4 = () => interopDefault(import('..\\pages\\costumer\\index.vue' /* webpackChunkName: "pages/costumer/index" */))
const _c473121e = () => interopDefault(import('..\\pages\\gapoktan\\index.vue' /* webpackChunkName: "pages/gapoktan/index" */))
const _573690d2 = () => interopDefault(import('..\\pages\\login.vue' /* webpackChunkName: "pages/login" */))
const _14e1220b = () => interopDefault(import('..\\pages\\poktan\\index.vue' /* webpackChunkName: "pages/poktan/index" */))
const _49420457 = () => interopDefault(import('..\\pages\\profile.vue' /* webpackChunkName: "pages/profile" */))
const _32e1a5e5 = () => interopDefault(import('..\\pages\\register.vue' /* webpackChunkName: "pages/register" */))
const _c71a5d58 = () => interopDefault(import('..\\pages\\test.vue' /* webpackChunkName: "pages/test" */))
const _31dd6898 = () => interopDefault(import('..\\pages\\admin\\produk\\index.vue' /* webpackChunkName: "pages/admin/produk/index" */))
const _f4b33e60 = () => interopDefault(import('..\\pages\\costumer\\address.vue' /* webpackChunkName: "pages/costumer/address" */))
const _499877f4 = () => interopDefault(import('..\\pages\\costumer\\cart.vue' /* webpackChunkName: "pages/costumer/cart" */))
const _d2f7d2ec = () => interopDefault(import('..\\pages\\costumer\\komplen.vue' /* webpackChunkName: "pages/costumer/komplen" */))
const _6fe55fac = () => interopDefault(import('..\\pages\\costumer\\order.vue' /* webpackChunkName: "pages/costumer/order" */))
const _aedd4bb0 = () => interopDefault(import('..\\pages\\gapoktan\\user_poktan.vue' /* webpackChunkName: "pages/gapoktan/user_poktan" */))
const _4994c740 = () => interopDefault(import('..\\pages\\admin\\master\\admin.vue' /* webpackChunkName: "pages/admin/master/admin" */))
const _336ac805 = () => interopDefault(import('..\\pages\\admin\\master\\kategori.vue' /* webpackChunkName: "pages/admin/master/kategori" */))
const _f48ed0bc = () => interopDefault(import('..\\pages\\gapoktan\\poktan\\kegiatan.vue' /* webpackChunkName: "pages/gapoktan/poktan/kegiatan" */))
const _22e5d754 = () => interopDefault(import('..\\pages\\gapoktan\\poktan\\stok_lumbung.vue' /* webpackChunkName: "pages/gapoktan/poktan/stok_lumbung" */))
const _1025aa86 = () => interopDefault(import('..\\pages\\gapoktan\\poktan\\tandur.vue' /* webpackChunkName: "pages/gapoktan/poktan/tandur" */))
const _d8dfb5ce = () => interopDefault(import('..\\pages\\gapoktan\\produk\\manage_produk.vue' /* webpackChunkName: "pages/gapoktan/produk/manage_produk" */))
const _0eb612fa = () => interopDefault(import('..\\pages\\gapoktan\\produk\\orders.vue' /* webpackChunkName: "pages/gapoktan/produk/orders" */))
const _3f27a6ab = () => interopDefault(import('..\\pages\\poktan\\pelaporan\\kegiatan.vue' /* webpackChunkName: "pages/poktan/pelaporan/kegiatan" */))
const _60b439dd = () => interopDefault(import('..\\pages\\poktan\\pelaporan\\stok_lumbung.vue' /* webpackChunkName: "pages/poktan/pelaporan/stok_lumbung" */))
const _56eee7cf = () => interopDefault(import('..\\pages\\poktan\\pelaporan\\tandur.vue' /* webpackChunkName: "pages/poktan/pelaporan/tandur" */))
const _0abd1480 = () => interopDefault(import('..\\pages\\index.vue' /* webpackChunkName: "pages/index" */))

// TODO: remove in Nuxt 3
const emptyFn = () => {}
const originalPush = Router.prototype.push
Router.prototype.push = function push (location, onComplete = emptyFn, onAbort) {
  return originalPush.call(this, location, onComplete, onAbort)
}

Vue.use(Router)

export const routerOptions = {
  mode: 'history',
  base: '/',
  linkActiveClass: 'nuxt-link-active',
  linkExactActiveClass: 'nuxt-link-exact-active',
  scrollBehavior,

  routes: [{
    path: "/admin",
    component: _2fd3d565,
    name: "admin"
  }, {
    path: "/costumer",
    component: _a17ae0a4,
    name: "costumer"
  }, {
    path: "/gapoktan",
    component: _c473121e,
    name: "gapoktan"
  }, {
    path: "/login",
    component: _573690d2,
    name: "login"
  }, {
    path: "/poktan",
    component: _14e1220b,
    name: "poktan"
  }, {
    path: "/profile",
    component: _49420457,
    name: "profile"
  }, {
    path: "/register",
    component: _32e1a5e5,
    name: "register"
  }, {
    path: "/test",
    component: _c71a5d58,
    name: "test"
  }, {
    path: "/admin/produk",
    component: _31dd6898,
    name: "admin-produk"
  }, {
    path: "/costumer/address",
    component: _f4b33e60,
    name: "costumer-address"
  }, {
    path: "/costumer/cart",
    component: _499877f4,
    name: "costumer-cart"
  }, {
    path: "/costumer/komplen",
    component: _d2f7d2ec,
    name: "costumer-komplen"
  }, {
    path: "/costumer/order",
    component: _6fe55fac,
    name: "costumer-order"
  }, {
    path: "/gapoktan/user_poktan",
    component: _aedd4bb0,
    name: "gapoktan-user_poktan"
  }, {
    path: "/admin/master/admin",
    component: _4994c740,
    name: "admin-master-admin"
  }, {
    path: "/admin/master/kategori",
    component: _336ac805,
    name: "admin-master-kategori"
  }, {
    path: "/gapoktan/poktan/kegiatan",
    component: _f48ed0bc,
    name: "gapoktan-poktan-kegiatan"
  }, {
    path: "/gapoktan/poktan/stok_lumbung",
    component: _22e5d754,
    name: "gapoktan-poktan-stok_lumbung"
  }, {
    path: "/gapoktan/poktan/tandur",
    component: _1025aa86,
    name: "gapoktan-poktan-tandur"
  }, {
    path: "/gapoktan/produk/manage_produk",
    component: _d8dfb5ce,
    name: "gapoktan-produk-manage_produk"
  }, {
    path: "/gapoktan/produk/orders",
    component: _0eb612fa,
    name: "gapoktan-produk-orders"
  }, {
    path: "/poktan/pelaporan/kegiatan",
    component: _3f27a6ab,
    name: "poktan-pelaporan-kegiatan"
  }, {
    path: "/poktan/pelaporan/stok_lumbung",
    component: _60b439dd,
    name: "poktan-pelaporan-stok_lumbung"
  }, {
    path: "/poktan/pelaporan/tandur",
    component: _56eee7cf,
    name: "poktan-pelaporan-tandur"
  }, {
    path: "/",
    component: _0abd1480,
    name: "index"
  }],

  fallback: false
}

function decodeObj(obj) {
  for (const key in obj) {
    if (typeof obj[key] === 'string') {
      obj[key] = decode(obj[key])
    }
  }
}

export function createRouter () {
  const router = new Router(routerOptions)

  const resolve = router.resolve.bind(router)
  router.resolve = (to, current, append) => {
    if (typeof to === 'string') {
      to = normalizeURL(to)
    }
    const r = resolve(to, current, append)
    if (r && r.resolved && r.resolved.query) {
      decodeObj(r.resolved.query)
    }
    return r
  }

  return router
}
