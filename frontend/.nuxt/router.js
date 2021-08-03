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
const _4994c740 = () => interopDefault(import('..\\pages\\admin\\master\\admin.vue' /* webpackChunkName: "pages/admin/master/admin" */))
const _336ac805 = () => interopDefault(import('..\\pages\\admin\\master\\kategori.vue' /* webpackChunkName: "pages/admin/master/kategori" */))
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
    path: "/admin/master/admin",
    component: _4994c740,
    name: "admin-master-admin"
  }, {
    path: "/admin/master/kategori",
    component: _336ac805,
    name: "admin-master-kategori"
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
