
export const  menu = () => {
  return [
      {
          text: 'Home',
          icon: 'el-icon-s-home text-success',
          route: '/gapoktan/',
      },
      {
        text: 'Poktan',
        icon: 'el-icon-s-custom text-success',
        route: '/gapoktan/user_poktan',
      },
      {
        text: 'Produk',
        icon: 'el-icon-shopping-cart-2 text-success',
        children: [
          {
            text: "Manage Produk",
            icon: 'el-icon-postcard',
            route: '/gapoktan/produk/manage_produk'
          },
          {
            text: "Orders",
            icon: 'el-icon-postcard',
            route: '/gapoktan/produk/orders'
          },
        ]
      },
      {
        text: 'Pelaporan Poktan',
        icon: 'el-icon-document text-success',
        children: [
          {
            text: "Stok Lumbung",
            icon: 'el-icon-postcard',
            route: '/gapoktan/poktan/stok_lumbung'
          },
          {
            text: "Kegiatan Poktan",
            icon: 'el-icon-postcard',
            route: '/gapoktan/poktan/kegiatan'
          },
          {
            text: "Tandur",
            icon: 'el-icon-postcard',
            route: '/gapoktan/poktan/tandur'
          },
        ]
      },
  ]
};
