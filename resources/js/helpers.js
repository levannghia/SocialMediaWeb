export const isImage = (attachment) => {
    const mime = attachment.mime.split("/");
    return mime[0].toLowerCase() === "image";
  }