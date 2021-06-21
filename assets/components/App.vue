<template>
  <div class="row rounded-lg bg-white overflow-hidden shadow" style="width:690px">
    <!-- Users box-->
    <left/>
    <!-- Chat Box-->
    <router-view :key="$route.fullPath"></router-view>
  </div>
</template>

<script>
import Left from './left/Left.vue';
import Right from './right/Right.vue';
export default {
  components: { Left, Right }
}
</script>