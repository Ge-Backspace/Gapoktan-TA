
export const  menu = () => {
    return [
        {
            text: 'Home',
            icon: 'ni ni-shop text-primary',
            route: '/admin/beranda',
        },
        // {
        //     text: 'Employees',
        //     icon: 'el-icon-notebook-1 text-primary',
        //     route: '/admin/employees'
        // },
        {
            text: 'Time Management',
            icon: 'el-icon-time text-primary',
            // route: '/admin/kegiatan',
            children: [
                {
                    text: "Time Off",
                    icon: 'el-icon-postcard',
                    route: '/admin/time_management/timeoff'
                },
                {
                    text: "Attendance",
                    icon: 'el-icon-postcard',
                    route: '/admin/time_management/attendance'
                },
                {
                    text: "Calendar",
                    icon: 'el-icon-postcard',
                    route: '/admin/time_management/calendar'
                },
                // {
                //     text: "Timesheet",
                //     icon: 'el-icon-postcard',
                //     route: '/admin/time_management/timesheet'
                // },

            ]
        },
        // {
        //     text: 'Finances',
        //     icon: 'el-icon-tickets text-primary',
        //     // route: '/admin/berita',
        //     children: [
        //         {
        //             text: "Reimbursement",
        //             icon: 'el-icon-postcard',
        //             route: '/admin/finances/reimbursement'
        //         },
        //         {
        //             text: "Cash Advance Request",
        //             icon: 'el-icon-postcard',
        //             route: '/admin/finances/cash'
        //         },
        //         {
        //           text: "Loan",
        //           icon: 'el-icon-postcard',
        //           route: '/admin/finances/loan'
        //       },
        //     ]
        // },
        // {
        //     text: 'Company',
        //     icon: 'el-icon-warning-outline text-primary',
        //     route: '/admin/company'
        // },
        {
            text: 'Shift',
            icon: 'bx bx-shuffle text-primary',
            route: '/admin/shiftEmployee'
        },
        {
            text: 'Permissions',
            icon: 'bx bx-question-mark text-primary',
            // route: '/admin/permission'
            children: [
              {
                  text: "Permission Cuti",
                  icon: 'el-icon-postcard',
                  route: '/admin/permissions/cuti'
              },
              {
                text: "Permission Change Shift",
                icon: 'el-icon-postcard',
                route: '/admin/permissions/shift'
            },
            // {
            //     text: "Permission Overtime",
            //     icon: 'el-icon-postcard',
            //     route: '/admin/permissions/overtime'
            // },
            ]
        },
        // {
        //     text: 'Salary',
        //     icon: 'bx bx-money text-primary',
        //     route: '/admin/salary'
        // },
        {
            text: 'Report',
            icon: 'el-icon-document text-primary',
            route: '/admin/report'
        },
        {
            text: 'Master',
            icon: 'bx bx-data',
            // route: '/admin/berita',
            children: [
                {
                    text: "Master Company",
                    icon: 'el-icon-postcard',
                    route: '/admin/master/masterCompanies'
                },
                {
                  text: "Master Office",
                  icon: 'el-icon-postcard',
                  route: '/admin/master/masterOffice'
                },
                {
                    text: "Master Employee",
                    icon: 'el-icon-postcard',
                    route: '/admin/master/masterEmployee'
                },
                {
                    text: "Master Position",
                    icon: 'el-icon-postcard',
                    route: '/admin/master/masterPosition'
                },
                {
                    text: "Master Shift",
                    icon: 'el-icon-postcard',
                    route: '/admin/master/masterShift'
                },
                {
                    text: "Master Cuti",
                    icon: 'el-icon-postcard',
                    route: '/admin/master/masterCuti'
                },
                // {
                //     text: "Master Salary",
                //     icon: 'el-icon-money',
                //     route: '/admin/master/masterSalary'
                // },
            ]
        },
        // {
        //     text: 'Calendar',
        //     icon: 'el-icon-user text-primary',
        //     route: '/admin/users'
        // },

    ]
};
