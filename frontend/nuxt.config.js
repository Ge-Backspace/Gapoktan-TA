import {config} from './global.config'

export default {
  // Global page headers (https://go.nuxtjs.dev/config-head)
  head: {
    title: 'GapoktanApp',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' }
    ],
    link: [
      { rel: 'stylesheet', href: config.base + 'assets/vendor/element-ui/index.css' },
      { rel: 'icon', type: 'image/x-icon', href: '/gapoktan-icon.png' },
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/icon?family=Material+Icons' },
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i&display=swap' },
      { rel: 'stylesheet', href: 'https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css' },
      { rel: 'stylesheet', href: 'https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.0/css/bulma.min.css' },
    ],
    script: [
      {
        src: config.base + 'assets/vendor/jquery/dist/jquery.min.js'
      },
      {
        src: config.base + 'assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js'
      },
      {
        src: config.base + 'assets/vendor/js-cookie/js.cookie.js'
      },
      {
        src: config.base + 'assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js'
      },
      {
        src: config.base + 'assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js'
      },
      {
        src: config.base + 'assets/vendor/chart.js/dist/Chart.min.js'
      },
      {
        src: config.base + 'assets/vendor/chart.js/dist/Chart.extension.js'
      },
      {
        src: config.base + 'assets/js/argon.min001.js'
      },
      {
        src: 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js'
      }
      // {
      //   src: 'https://cdn.trackjs.com/agent/v3/latest/t.js'
      // }
    ],
  },

  // Global CSS (https://go.nuxtjs.dev/config-css)
  css: [
    '@static/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css',
    '@static/assets/vendor/nucleo/css/nucleo.css',
    '@static/assets/css/argon.min.css',
    '@static/assets/scss/jmli.scss',
    '@static/assets/scss/ckm.styl',
    'boxicons/css/boxicons.min.css'
  ],

  /*
  ** Customize the progress bar color
  */
 loading: {
    name: 'chasing-dots',
    color: '#E6A23C',
    background: 'white',
    height: '4px'
 },

  // Plugins to run before rendering page (https://go.nuxtjs.dev/config-plugins)
  plugins: [
    {src: '@plugins/helper'},
    {src: '@plugins/vuesax'},
    {src: '@plugins/extablecolumn'},
    {src: '@plugins/vuegeolocation'},
    {src: '@plugins/vuegooglemaps'},
    {src: '@plugins/vcalendar', mode: 'client'},
    {src: '@plugins/swal', mode: 'client'},
    {src: '@plugins/vue2editor', mode: 'client'},
    {src: "@/plugins/chart", mode: 'client' }
  ],

  // Auto import components (https://go.nuxtjs.dev/config-components)
  components: true,

  // Modules for dev and build (recommended) (https://go.nuxtjs.dev/config-modules)
  buildModules: [
  ],

  // Modules (https://go.nuxtjs.dev/config-modules)
  modules: [
    'nuxt-element-ui',
    '@nuxtjs/axios',
    '@nuxtjs/auth',
    '@nuxtjs/moment',
  ],


  module: {
    rules: [
        {
           test: /\.s[ac]ss$/i,
           use: ['style-loader','css-loader','sass-loader',],
         },
         {
          test: /\.css$/,
          loader: 'vue-style-loader!css-loader'
        }
    ],
  },

  loaders: {

    // ...

    // Css loader for Webpack 1.x .
    css: 'vue-style-loader!css-loader'
  },

  // Build Configuration (https://go.nuxtjs.dev/config-build)
  build: {
    transpile: [/^vue2-google-maps($|\/)/],
  },
  /*
  ** Customize the progress bar color
  */
  loading: {
    name: 'chasing-dots',
    color: '#E6A23C',
    background: 'white',
    height: '4px'
  },

  axios: {
    baseURL: config.baseApiUrl,
    headers: {
      'Content-Type':'application/json',
      'X-Requested-With':'XMLHttpRequest'
    }
  },

  auth: {
    busy:false,
    loggedIn:true,
    strategies: {
      local: {
        endpoints: {
          login: {url: '/login', method: 'post', propertyName: 'data.token'},
          logout: false,
          user: {url: '/user', method: 'get', propertyName: 'data'},
        },
        tokenRequired: true,
        tokenType: 'Bearer'
      }
    },
    redirect: {
      login: '/login',
      logout: '/login',
      user: '/profile',
      callback:'/'
    }
  },

  extend (config, { isDev, isClient }) {
    if (isDev && isClient) {
      config.module.rules.push({
        test: /\.(js|vue)$/,
        enforce: 'pre',
        loader: 'eslint-loader',
        exclude: /(node_modules)/
      });
      config.module.rules.push({
        test: /\.css$/,
        loader: ['css-loader', 'stylus-loader'],
        exclude: /(node_modules)/
      });
    }
  }
}
