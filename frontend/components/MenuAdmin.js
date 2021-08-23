
export const  menu = () => {
    return [
        {
            text: 'Home',
            icon: 'ni ni-shop text-success',
            route: '/admin/',
        },
        {
          text: 'Produk',
          icon: 'el-icon-shopping-cart-2 text-success',
          route: '/admin/produk',
        },
        {
          text: 'Order',
          icon: 'el-icon-box text-success',
          route: '/admin/order',
        },
        {
          text: 'User',
          icon: 'el-icon-s-custom text-success',
          children: [
            {
              text: "Admin",
              icon: 'el-icon-postcard',
              route: '/admin/user/admin'
            },
            {
              text: "Gapoktan",
              icon: 'el-icon-postcard',
              route: '/admin/user/gapoktan'
            },
            {
              text: "Costumer",
              icon: 'el-icon-postcard',
              route: '/admin/user/costumer'
            },
          ]
        },
        {
          text: 'Master',
          icon: 'bx bx-data text-success',
          children: [
            {
              text: "Kategori Produk",
              icon: 'el-icon-postcard',
              route: '/admin/master/kategori'
            },
          ]
        },
    ]
};
