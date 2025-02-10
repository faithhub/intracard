// CreditGauge.vue
<template>
  <div class="credit-gauge">
    <div class="d-flex justify-space-between align-center mb-3">
      <h3 class="text-h6 font-weight-bold mb-0">Credit Score</h3>
      <v-switch
        inset
        color="purple"
        v-model="isTransUnion"
        @update:model-value="updateCardsVisibility"
        :true-value="'TransUnion'"
        :false-value="'Equifax'"
      ></v-switch>
    </div>
    <div style="align-items: center;">
        <canvas ref="gaugeCanvas" width="300" height="300" data-animation-duration="3500"></canvas>
    </div>
    <div class="score-controls text-center mt-4">
      <div class="font-weight-bold" style="font-size:18px">{{ isTransUnion }}</div>
      <p class="text-sm text-gray-600">Credit Score: {{ currentScore }}</p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch, nextTick } from 'vue'
import { RadialGauge } from 'canvas-gauges/gauge.min'

export default {
  name: 'CreditGauge',
  setup() {
    const gaugeCanvas = ref(null)
    const isTransUnion = ref('Equifax')
    const currentScore = ref(300)
    let creditGauge = null

    const initializeGauge = () => {
      if (!gaugeCanvas.value) return

      creditGauge = new RadialGauge({
        renderTo: gaugeCanvas.value,
        width: 300,
        height: 300,
        units: "Credit Score",
        minValue: 300,
        maxValue: 900,
        majorTicks: ["300", "400", "500", "600", "700", "800", "900"],
        minorTicks: 2,
        strokeTicks: true,
        highlights: [
          {
            from: 300,
            to: 500,
            color: "#FF4D4F"
          },
          {
            from: 500,
            to: 700,
            color: "#FFC53D"
          },
          {
            from: 700,
            to: 900,
            color: "#40C057"
          }
        ],
        colorPlate: "#f0f0f0",
        colorMajorTicks: "#000",
        colorMinorTicks: "#333",
        colorNumbers: "#000",
        colorNeedle: "black",
        colorNeedleEnd: "black",
        needleType: "arrow",
        needleWidth: 3,
        animationDuration: 1500,  // Reduced animation duration
        animationRule: "linear",
        value: 300
      }).draw()
    }

    const updateGauge = (creditScore) => {
      if (creditGauge) {
        // Update both the gauge and the displayed score
        creditGauge.value = creditScore
        creditGauge.update({ value: creditScore }) // Added explicit update call
        currentScore.value = creditScore
      }
    }

    const updateCardsVisibility = (value) => {
      // Get the current switch value
      const currentValue = value || isTransUnion.value
      const score = currentValue === 'TransUnion' ? 560 : 600
      
      // Ensure the gauge exists before updating
      if (creditGauge) {
        updateGauge(score)
      } else {
        // If gauge doesn't exist yet, initialize it first
        nextTick(() => {
          initializeGauge()
          updateGauge(score)
        })
      }
    }

    onMounted(() => {
      nextTick(() => {
        initializeGauge()
        // Initial update with the default value
        updateCardsVisibility()
      })
    })

    // Watch for changes in isTransUnion
    watch(isTransUnion, (newValue) => {
      updateCardsVisibility(newValue)
    }, { immediate: true })

    return {
      gaugeCanvas,
      isTransUnion,
      currentScore,
      updateCardsVisibility
    }
  }
}
</script>

<style scoped>
.credit-gauge {
  display: flex;
  flex-direction: column;
}

.score-controls {
  margin-top: 20px;
}
</style>