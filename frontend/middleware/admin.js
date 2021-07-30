import axios from 'axios'

export default async function ({ $auth, next }) {
  let user = $auth.state.user;
  if (user) {
    // User ada
    axios.get('/account/'+user.id)
    .then((resp) => {
      if(resp.data.data == 1) {
        // Jika account admin biarkan
      } else {
        // Akun bukan admin, kembalikan ke index
        next('/')
      }
    })
  } else {
    // User tidak ada, kembalikan ke index
    next('/');
  }

}
