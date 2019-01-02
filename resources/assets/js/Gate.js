export default class Gate {
    constructor(user) {
        this.user = user;
    }

    isAdmin() {
        return this.user.type === "admin";
    }

    isUser() {
        return this.user.type === "user";
    }

    isUploader() {
        return this.user.type === "uploader";
    }

    isAdminOrUploader() {
        if (this.user.type == "admin" || this.user.type == "uploader") {
            return true;
        } else {
            return false;
        }
    }
    isAdminOrAuthor() {
        if (this.user.type === "admin" || this.user.type === "author") {
            return true;
        }
    }
    isAuthorOrUser() {
        if (this.user.type === "user" || this.user.type === "author") {
            return true;
        }
    }
}
