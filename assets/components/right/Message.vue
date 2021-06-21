<template>
<div>
    <!-- Sender Message-->
        <div class="media w-50 mb-3" :class="{'ml-auto': message.mine}">
          <img v-if="!message.mine" :src="'/Uploads/Users/'+message.picture" alt="user" width="35" height="35" class="rounded-circle">
          <div class="media-body ml-3">
            <div class="box rounded py-2 px-3 mb-2" :class="[message.mine ? 'bg-dark' : 'bg-light']">
              <p class="text-small mb-0" :class="[message.mine ? 'text-white' : 'text-muted']">{{message.content}}</p>
            </div>
            <p class="small text-muted">{{ date }}</p>
          </div>
        </div>
</div>
</template>

<script>
export default {
    props: {
      message: Object
    },
    computed: {
        date(){
            return new Date(this.message.createdAt).toLocaleString();
        }
    }
}
</script>
<style>
.box{
  border: .1px solid rgb(48, 46, 46) !important ;
}
</style>