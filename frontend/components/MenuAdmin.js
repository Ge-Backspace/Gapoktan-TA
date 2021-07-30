
export const  menu = () => {
    return [
        {
            text: 'Home',
            icon: 'ni ni-shop text-primary',
            route: '/admin/',
        },
        {
          text: 'Pelaporan',
          icon: 'bx bx-file text-primary',
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
    ]
};
