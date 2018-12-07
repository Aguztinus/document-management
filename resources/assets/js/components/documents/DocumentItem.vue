<template>
  <div class="wrapdoc">
    <svg
      v-if="doc.file_ext == 'pdf'"
      version="1.1"
      preserveAspectRatio="xMidYMid meet"
      xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink"
      viewBox="0 0 32 32"
      width="32"
      height="32"
      enable-background="new 0 0 32 32"
      xml:space="preserve"
      role="img"
    >
      <path fill="#fff" d="M25 27H7V5h13l5 5v17"></path>
      <path
        d="M20 4H7a1 1 0 0 0-1 1v22a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V9.89zm5 23H7V5h13v4a1 1 0 0 0 1 1h4z"
        fill="#e33d55"
      ></path>
      <path
        d="M21.53 20.17a2 2 0 0 0-1.76-.93 10 10 0 0 0-1.54.11 4.77 4.77 0 0 0 3.13.81 1 1 0 0 1-.84.86 2.4 2.4 0 0 1-1.52-.21 17.59 17.59 0 0 1-1.73-1 .55.55 0 0 0-.54-.09l-2.58.93a.85.85 0 0 0-.37.3c-1.29 1.95-2.44 3-2.93 3.06s-1.13-.18-.86-1.37 1.23-1.49 2.29-1.74c-1.08.8-1.5 1.42-1.41 2.1a.83.83 0 0 0 .13-.14 24.24 24.24 0 0 0 3.8-5.45.4.4 0 0 0 0-.41 6.94 6.94 0 0 1-.9-3.66c.26-2.53 2.55-1.55 2 .38a1.87 1.87 0 0 0-.51-1.1c-.29-.22-.54-.12-.59.23a5.77 5.77 0 0 0 .51 3.35 5.46 5.46 0 0 0 .46-2.2.9.9 0 0 1 .52.62 2.8 2.8 0 0 1-.08 1.62c-.08.28-.2.56-.28.84a.34.34 0 0 0 0 .27c.37.42.75.82 1.15 1.22a.36.36 0 0 0 .3 0 7.22 7.22 0 0 1 2.11-.44 1.82 1.82 0 0 1 2 1.62zm-6-2.12l-1 1.84 1.9-.88z"
        fill="#e33d55"
      ></path>
    </svg>
    <svg
      v-else-if="doc.file_ext == 'zip' || doc.file_ext == 'rar' || doc.file_ext == '7z' "
      version="1.1"
      preserveAspectRatio="xMidYMid meet"
      xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink"
      viewBox="0 0 32 32"
      width="32"
      height="32"
      enable-background="new 0 0 32 32"
      xml:space="preserve"
      role="img"
    >
      <path fill="#fff" d="M25 27H7V5h13l5 5v17z"></path>
      <path
        fill="#737f8b"
        d="M11 25h1v1h-1zM11 23h1v1h-1zM11 21h1v1h-1zM11 19h1v1h-1zM11 17h1v1h-1zM11 15h1v1h-1zM11 13h1v1h-1zM11 11h1v1h-1zM11 9h1v1h-1zM11 7h1v1h-1zM11 5h1v1h-1zM12 24h1v1h-1zM12 22h1v1h-1zM12 20h1v1h-1zM12 18h1v1h-1zM12 16h1v1h-1zM12 14h1v1h-1zM12 12h1v1h-1zM12 10h1v1h-1zM12 8h1v1h-1zM12 6h1v1h-1z"
      ></path>
      <path
        d="M20 4H7a.94.94 0 0 0-1 1v22a.94.94 0 0 0 1 1h18a.94.94 0 0 0 1-1V9.9zm5 23H7V5h13v4a.94.94 0 0 0 1 1h4z"
        fill="#737f8b"
      ></path>
      <path fill="#737f8b" d="M12 26h1v1h-1z"></path>
    </svg>
    <svg
      v-else-if="doc.file_ext == 'xls' || doc.file_ext == 'xlsx' "
      version="1.1"
      preserveAspectRatio="xMidYMid meet"
      xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink"
      viewBox="0 0 32 32"
      width="32"
      height="32"
      enable-background="new 0 0 32 32"
      xml:space="preserve"
      role="img"
    >
      <path d="M25 27H7V5h13l5 5z" fill="#FFFFFF"></path>
      <path
        d="M20 4H7c-.6 0-1 .4-1 1v22c0 .6.4 1 1 1h18c.6 0 1-.4 1-1V9.9L20 4zm5 23H7V5h13v4c0 .6.4 1 1 1h4v17z"
        fill="#207245"
      ></path>
      <path
        d="M10 13h3v2h-3zm4 0h3v2h-3zm4 0h3v2h-3zm-8 3h3v2h-3zm4 0h3v2h-3zm4 0h3v2h-3zm-8 3h3v2h-3zm4 0h3v2h-3zm4 0h3v2h-3zm-8 3h3v2h-3zm4 0h3v2h-3zm4 0h3v2h-3z"
        fill="#207245"
      ></path>
    </svg>
    <svg
      v-else-if="doc.file_ext == 'png' || doc.file_ext == 'jpg' || doc.file_ext == 'gif'"
      version="1.1"
      preserveAspectRatio="xMidYMid meet"
      xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink"
      viewBox="0 0 32 32"
      width="32"
      height="32"
      enable-background="new 0 0 32 32"
      xml:space="preserve"
      role="img"
    >
      <path fill="#fff" d="M25 27H7V5h13l5 5v17z"></path>
      <path
        d="M20 4H7a1 1 0 0 0-1 1v22a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V9.89zm5 23H7V5h13v4a1 1 0 0 0 1 1h4z"
        fill="#3fb87f"
      ></path>
      <path
        d="M18 17a2 2 0 1 1 2-2 2 2 0 0 1-2 2zm-.6 4.09l-3.12-3.71a.41.41 0 0 0-.58-.06l-.06.06L9 23h13l-2.72-3.6a.35.35 0 0 0-.6 0z"
        fill="#3fb87f"
      ></path>
    </svg>
    <svg
      v-else-if="doc.file_ext == 'txt' || doc.file_ext == 'doc' || doc.file_ext == 'docx'"
      version="1.1"
      preserveAspectRatio="xMidYMid meet"
      xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink"
      viewBox="0 0 32 32"
      width="32"
      height="32"
      enable-background="new 0 0 32 32"
      xml:space="preserve"
      role="img"
    >
      <path
        fill-rule="evenodd"
        clip-rule="evenodd"
        fill="#3464AF"
        d="M24.5,27.5h-17c-0.6,0-1-0.4-1-1v-21c0-0.6,0.4-1,1-1h12l6,6v16 C25.5,27.1,25.1,27.5,24.5,27.5z"
      ></path>
      <path
        fill-rule="evenodd"
        clip-rule="evenodd"
        fill="#FFFFFF"
        d="M24,26.5H8c-0.3,0-0.5-0.2-0.5-0.5V6c0-0.3,0.2-0.5,0.5-0.5h11.5 l5,5V26C24.5,26.3,24.3,26.5,24,26.5z"
      ></path>
      <path
        fill-rule="evenodd"
        clip-rule="evenodd"
        fill="#3464AF"
        d="M19.5,4.5l6,6h-5c-0.6,0-1-0.4-1-1V4.5z"
      ></path>
      <rect
        x="10.5"
        y="14.5"
        fill-rule="evenodd"
        clip-rule="evenodd"
        fill="#3464AF"
        width="11"
        height="1"
      ></rect>
      <rect
        x="10.5"
        y="17.5"
        fill-rule="evenodd"
        clip-rule="evenodd"
        fill="#3464AF"
        width="11"
        height="1"
      ></rect>
      <rect
        x="10.5"
        y="20.5"
        fill-rule="evenodd"
        clip-rule="evenodd"
        fill="#3464AF"
        width="8"
        height="1"
      ></rect>
    </svg>
    <svg
      v-else
      version="1.1"
      preserveAspectRatio="xMidYMid meet"
      xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink"
      viewBox="0 0 32 32"
      width="32"
      height="32"
      enable-background="new 0 0 32 32"
      xml:space="preserve"
      role="img"
    >
      <path fill="#fff" d="M25 27H7V5h13l5 5v17z"></path>
      <path
        d="M20 4H7a1 1 0 0 0-1 1v22a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V9.89zm5 23H7V5h13v4a1 1 0 0 0 1 1h4z"
        fill="#737f8b"
      ></path>
    </svg>
    <div class="card-body">
      <div class="filename">
        <a href="#">{{doc.name}}</a>
      </div>
      <div class="filedate">
        <small class="text-muted">{{doc.created_at | myDateshort}}, by {{doc.userowner.name}}</small>
      </div>
      <div class="filesize">
        <small class="text-muted">{{doc.size}}</small>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["doc"]
};
</script>

