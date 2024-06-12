/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primaryColor: '#013E5B',
        primaryColorHover: '#012D42',
        primaryColorHover2: '#0358a8',
        headerBGColor: '#003249',
        sideBarBGColorHover: '#034C6F',
        dashboardNavItemHoverColor: '#F9BC35',
        headingTextColor: '#A87123',
        registrationInputFieldsBGColor: '#F5F5F5',
        registrationCardBGColor: '#E3EDF6',
      },
      backgroundImage: {
        loginBg: "url('/public/assets/images/loginbg.png')",
      },
      gridTemplateColumns: {
        attendeeDetailGrid: '320px auto',
        attendeeDetailGrid2: '230px auto',
        attendeeDetailGrid3: '140px auto',
      },
    },
  },
  plugins: [],
}
