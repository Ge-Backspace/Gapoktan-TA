import axios from 'axios'

export default async function ({ $auth, next }) {
  let user = $auth.state.user;
  if (user) {
    // User ada
    axios.get('/account/'+user.id)
    .then((resp) => {
      if(resp.data.data == 4) {
        // Jika account costumer biarkan
      } else {
        // Akun bukan costumer, kembalikan ke index
        next('/')
      }
    })
  } else {
    // User tidak ada, kembalikan ke index
    next('/');
  }

}
