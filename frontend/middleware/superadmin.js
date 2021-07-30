export default async function ({ $auth, next }) {
  let user = $auth.state.user;
  if (user) {
    // User ada
    if (user.role_id != 1) {
      next('/');
    }
  } else {
    // User tidak ada dan dikembalikan
    next('/');
  }

}
