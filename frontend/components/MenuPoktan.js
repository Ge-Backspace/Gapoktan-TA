
export const  menu = () => {
  return [
      {
          text: 'Home',
          icon: 'el-icon-s-home text-success',
          route: '/poktan/',
      },
      {
        text: 'Pelaporan',
        icon: 'el-icon-document text-success',
        children: [
          {
            text: "Kegiatan",
            icon: 'el-icon-postcard',
            route: '/poktan/pelaporan/kegiatan'
          },
          {
            text: "Tandur",
            icon: 'el-icon-postcard',
            route: '/poktan/pelaporan/tandur'
          },
          {
            text: "Stok Lumbung",
            icon: 'el-icon-postcard',
            route: '/poktan/pelaporan/stok_lumbung'
          },
        ]
      },
  ]
};
