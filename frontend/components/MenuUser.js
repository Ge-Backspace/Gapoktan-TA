
export const  menu = () => {
  return [
      {
          text: 'Home',
          icon: 'ni ni-shop text-primary',
          route: '/user/beranda',
      },
      {
          text: 'Shift',
          icon: 'bx bx-shuffle text-primary',
          route: '/user/shift_employee'
      },
      {
        text: 'Time Management',
        icon: 'el-icon-time text-primary',
        children: [
              {
                  text: "Time Off",
                  icon: 'el-icon-postcard',
                  route: '/user/time_management/timeoff'
              },
              {
                  text: "Attendance",
                  icon: 'el-icon-postcard',
                  route: '/user/time_management/attendance'
              },
              {
                  text: "Calendar",
                  icon: 'el-icon-postcard',
                  route: '/user/time_management/calendar'
              },
          ]
      },
      {
          text: 'Permissions',
          icon: 'bx bx-question-mark text-primary',
          children: [
            {
              text: "Permission Cuti",
              icon: 'el-icon-postcard',
              route: '/user/permissions/cuti'
            },
            {
              text: "Permission Change Shift",
              icon: 'el-icon-postcard',
              route: '/user/permissions/shift'
            },
            {
              text: "Permission Overtime",
              icon: 'el-icon-postcard',
              route: '/user/permissions/overtime'
            },
          ]
      },
      // {
      //     text: 'Salary',
      //     icon: 'bx bx-money text-primary',
      //     route: '/user/salary'
      // },
  ]
};
