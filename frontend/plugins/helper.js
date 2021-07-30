import Vue from 'vue'

Vue.mixin({
  data() {
    return {
      globalLoader: false
    }
  },
  methods: {
    humanize(str) {
      let frags = str.split('_');
      for (let i = 0; i < frags.length; i++) {
        frags[i] = frags[i].charAt(0).toUpperCase() + frags[i].slice(1);
      }
      return frags.join(' ');
    },
    showErrorField(data) {
      Object.keys(data).forEach((v, k) => {
        setTimeout(() => {
          // this.$notify.error({
          //     title: this.humanize(v),
          //     message: data[v]
          // });
          this.openNotification('top-right', 'danger', `<i class='bx bxs-error-circle'></i>`, this.humanize(v), data[v])
        }, 500)
      });
    },
    isEmptyObj(obj) {
      return !Object.keys(obj).length;
    },
    changeDateFormat(string) {
      //dd-mm-yyyy
      let arr = string.toString().split('-');
      //0,1,2

      if (arr.length > 2) {
        return arr[2] + '-' + arr[1] + '-' + arr[0];
      }

      return null;
    },
    changeDateTimeFormat(string) {
      let arr = string.toString().split(' ');
      if (arr.length > 0) {
        return this.changeDateFormat(arr[0]) + " " + arr[1];
      }
      return null;
    },
    numberWithCommas(x) {
      return x.toString().replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },
    currentDate() {
      let today = new Date();
      let date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
      return date;
    },
    openNotification(position = null, color, icon, title, message) {
      const noti = this.$vs.notification({
        icon,
        color,
        position,
        title: title,
        text: message
      })
    },
    truncateString(str, num) {
      if (str.length > num) {
        return str.slice(0, num) + "...";
      } else {
        return str;
      }
    },
    downloadFile(url, debug = false) {
      this.globalLoader = true
      this.$axios.get(url, {
          responseType: 'arraybuffer',
          headers: {
            'Content-Type': 'application/json',
          }
        })
        .then((response) => {
          let filename = response.headers['content-disposition'].replace("attachment; filename=", '')
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', filename); //or any other extension
          document.body.appendChild(link);
          link.click();
        })
        .catch((error) => {
          if(debug){
            this.$notify.error({title: 'Gagal Mendownload', message: 'File Tidak Tersedia'})
          }
        })
        .finally(()=>{
          this.globalLoader = false
        });
    }
  }
});
