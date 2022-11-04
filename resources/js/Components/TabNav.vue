<script>

Vue.component('tabs', {
  template: `
    <div>
      <div class="tabs">
        <ul>
          <li v-for="tab in tabs" :class="{ 'is-active': tab.isActive }">
            <a :href="tab.href" @click="selectTab(tab)">{{ tab.name }}</a>
          </li>
        </ul>
      </div>

      <div class="tabs-details">
        <slot></slot>
      </div>
    </div>
  `,
  data() {
    return { tabs: [] }
  },
  created() {
    this.tabs = this.$children;
  },
  methods: {
    selectTab: function(selectedTab) {
      this.tabs.forEach(tab => {
        tab.isActive = (tab.name == selectedTab.name);
      });
    }
  }
});

Vue.component('tab', {
  props: {
    name: { required: true },
    selected: { default: false }
  },
  template: `<div v-show="isActive"><slot></slot></div>`,
  data() {
    return { isActive: false }
  },
  computed: {
    href() {
      return '#' + this.name.toLowerCase().replace(/ /g , '-');
    }
  },
  mounted() {
    this.isActive = this.selected;
  }
});

new Vue({
  el: '#app',
});

</script>
<template>
 <div id="app" class="container">
  <tabs>
    <tab name="Services" :selected="true">
      <ul>
        <li>Cool services we offer</li>
        <li>And another cool service we offer</li>
      </ul>
    </tab>
    <tab name="About">
      <p>Here is info about us.</p>  
    </tab>
    <tab name="Contact">
      <p>Conact us if you want.</p>  
    </tab>
  </tabs>
</div>
</template>
