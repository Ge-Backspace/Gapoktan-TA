import axios from 'axios'

export default async function ({ $auth, next }) {
  let user = $auth.state.user;
  if (user) {
    // User ada
    axios.get('/account/'+user.id)
    .then((resp) => {
      if(resp.data.data == 2) {
        // Jika account gapoktan biarkan
      } else {
        // Akun bukan gapoktan, kembalikan ke index
        next('/')
      }
    })
  } else {
    // User tidak ada, kembalikan ke index
    next('/');
  }

}
