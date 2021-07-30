import {
    config
  } from '../global.config';

class UploadFilesService {
    upload(file, onUploadProgress, name) {
      let formData = new FormData();
  
      formData.append("file", file);
      formData.append("name", name);
  
      return config.post("/test", formData, {
        headers: {
          "Content-Type": "multipart/form-data"
        },
        onUploadProgress
      });
    }
  }
  
  export default new UploadFilesService();