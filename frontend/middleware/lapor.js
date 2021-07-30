export default function ({ store, redirect }) {
    // If the user is not authenticated
    if (!store.state.lapor.laporan) {
      return redirect('/admin/lapor')
    }
  }