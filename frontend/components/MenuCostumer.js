
export const menu = () => {
  return [
    {
      text: 'Home',
      icon: 'el-icon-s-home text-success',
      route: '/costumer/',
    },
    {
      text: 'Alamat',
      icon: 'el-icon-map-location text-success',
      route: '/costumer/address',
    },
    {
      text: 'Keranjang',
      icon: 'el-icon-shopping-cart-2 text-success',
      route: '/costumer/cart',
    },
    {
      text: 'Order',
      icon: 'el-icon-box text-success',
      route: '/costumer/order',
    },
    // {
    //   text: 'Komplen',
    //   icon: 'el-icon-s-home text-success',
    //   route: '/costumer/komplen',
    // },
  ]
};
