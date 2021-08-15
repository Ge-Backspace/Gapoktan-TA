
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
          text: 'Master',
          icon: 'bx bx-data text-success',
          children: [
            {
              text: "Kategori Produk",
              icon: 'el-icon-postcard',
              route: '/admin/master/kategori'
            },
            {
              text: "Admin",
              icon: 'el-icon-postcard',
              route: '/admin/master/admin'
            },
            {
              text: "Gapoktan",
              icon: 'el-icon-postcard',
              route: '/admin/master/kategori'
            },
            {
              text: "Poktan",
              icon: 'el-icon-postcard',
              route: '/admin/master/kategori'
            },
            {
              text: "Costumer",
              icon: 'el-icon-postcard',
              route: '/admin/master/kategori'
            },
          ]
      },
    ]
};
