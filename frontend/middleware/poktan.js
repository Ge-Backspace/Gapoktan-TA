import axios from 'axios'

export default async function ({ $auth, next }) {
  let user = $auth.state.user;
  if (user) {
    // User ada
    axios.get('/account/'+user.id)
    .then((resp) => {
      if(resp.data.data == 3) {
        // Jika account poktan biarkan
      } else {
        // Akun bukan poktan, kembalikan ke index
        next('/home')
      }
    })
  } else {
    // User tidak ada, kembalikan ke index
    next('/home');
  }

}
