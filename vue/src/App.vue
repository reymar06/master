<template>
  <div id="app">
    
    <TheHeader />

    
    
    <main class="content">
        <div class="input-row">
          <div class="input-card">
    
            <div class="card-header">
              <h2>家づくりスタートBOOK 回答内容確認シート</h2>
            
            <button class="print-btn" onclick="window.print()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="6 9 6 2 18 2 18 9"></polyline>
                  <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                  <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print Page
              </button> 
            </div>
            
          <div class="card-body">
            <div class="form-group">
              <input 
                  type="text" 
                  v-model="customerName"  
                  placeholder="お客様名" 
              />
            </div>
            
            <div class="form-group">
              <input 
                  type="text" 
                  v-model="customerCode" 
                  placeholder="お客様コード"
                  @keyup="getClientName"
              />
            </div>
            
            <div class="form-group">
              <div class="select-wrapper">
                <select  
                  v-model="houseType"
                  :disabled="houseTypeDisabled" 
                  required
                  @change="changeHouseType"
                >
                    <option 
                      v-for="type in houseTypes" 
                      :key="type.houseTypeCode" 
                      :value="type.houseTypeCode"
                    >
                      {{ type.houseTypeName }}
                    </option>
                </select>
              </div>
            </div>
        </div>
      </div>
  </div>



      <div v-if="houseType" >
          <div v-for="(step, i) in stepList" :key="i" >
            <h3>{{ step.stepName}}</h3>
            <div class="summary-card-container">
              <div class="step-card">
                   <div class="table-headers-row">
                      <div class="th-label">質問</div>
                      <div class="th-label">選択</div>
                    </div>
                 
              <div 
                v-for="(question, j) in step.questionList" 
                :key="j" 
                class="question-row-item"
              >
                <div class="question-col">
                  <span class="mobile-only-label">質問</span>
                  <p class="question-text">{{ question.question }}</p>
                </div>
                
                <div class="answer-col">
                  <span class="mobile-only-label">選択</span>
                  
                  <div class="choices-list">
                    <div 
                      v-for="(choice, k) in question.choiceList" 
                      :key="k" 
                      class="choice-element"
                    >
                      <div class="status-indicator" :class="{ 'selected-active': choice.selected }">
                        <svg 
                          v-if="choice.selected"
                          class="checkmark-svg" 
                          viewBox="0 0 24 24" 
                          fill="none" 
                          stroke="currentColor" 
                          stroke-width="3" 
                          stroke-linecap="round" 
                          stroke-linejoin="round"
                        >
                          <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                      </div>
                      
                      <div class="choice-content-wrapper">
                        <span class="choice-text" :class="{ 'text-bold': choice.selected }">
                          {{ choice.choice }}
                        </span>
                        
                        <div v-if="choice.imageFileName" class="choice-img-box">
                          <img
                            :src="`https://ichijo-itab.s3.ap-northeast-1.amazonaws.com/hsb_image${choice.imageFileName}`"
                            alt="Choice Image"
                            class="embedded-img"
                          />
                        </div>
                      </div>

                    </div>
                  </div>
                  
                </div>
              </div> 
          </div>
        </div>
         </div>
        </div>
      <div v-else class="illustration-container">
        <img :src="noDataImage" alt="No Data Found"
         class="responsive-img"
        />
      </div>
      

      
    </main>

   <div v-if="overlay" id="loading-overlay" class="overlay">
      <div class="overlay-content">
        <div class="progress-circular"></div>
        <div class="loading-text">
          データを読み込んでいます...
        </div>
      </div>
    </div>
</div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import TheHeader from './components/TheHeader.vue'
import noDataImage from './assets/hand-drawn-no-data-concept.png'
import api from './api.js';

// UI State Management Variables
const overlay = ref(false)
const houseType = ref(null)
const houseTypeDisabled = ref(true)
const customerName = ref(null)
const customerCode = ref('')
const stepList = ref([]);

// Dropdown Content
const houseTypes = ref([
  { houseTypeCode: 2, houseTypeName: "(洋風)A"},
  { houseTypeCode: 5, houseTypeName: "(洋風)F2"},
  { houseTypeCode: 11, houseTypeName: 'ブリアール' },
  { houseTypeCode: 12, houseTypeName: 'i-cube' },
  { houseTypeCode: 15, houseTypeName: 'i-smart' },
  { houseTypeCode: 20, houseTypeName: 'グラン・セゾン' },
  { houseTypeCode: 21, houseTypeName: 'グラン・スマート' },
  { houseTypeCode: 29, houseTypeName: 'Naturia(ナチュリア)' },
])

/**
 * Validates the typed code layout and executes async API data request
 */async function getClientName() {
  // 1. Validate format code signature rules layout early (e.g. 1234567-ABCD)
  const isValidPattern = /^[0-9A-Z]{7}-[0-9A-Z]{4}$/i.test(customerCode.value || '')
  
  if (!isValidPattern) {
    houseTypeDisabled.value = true
    customerName.value = null
    houseType.value = null
    return // Exits early safely BEFORE the overlay is ever turned on
  }

  // 2. Reset dependent values and trigger the overlay screen block
  overlay.value = true
  houseType.value = null
  customerName.value = null

  try {
    // 3. Send request to proxy API route targets
    const response = await api.post('/api/get_client_name', {
      customerCode: customerCode.value,
    }, {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
    })

    // 4. Parse returned database responses (Safe fallback if data structure changes)
    const clientData = response.data?.[0] || response.data
    customerName.value = clientData?.ConstructionName || null
    
    // Unlock option selection panel if record matched cleanly
    houseTypeDisabled.value = !customerName.value

  } catch (error) {
    console.error('Failed to fetch client data:', error)
    customerName.value = null
    houseTypeDisabled.value = true
  } finally {
    // 5. Guaranteed execution: Safely dismount view mask block no matter what
    overlay.value = false
  }
}

async function changeHouseType(event) {
  // 1. Safely extract value (Prioritize reactive state, fall back to event target)
  const selectedValue = houseType.value || event?.target?.value
  if (!selectedValue) return

  // 2. Clear previous data and lock UI with overlay
  stepList.value = [] // Prevents old data from showing if the API fails
  overlay.value = true

  try {
    // 3. Post data payload to Laravel backend
    const response = await api.post('/api/get_data', {
      customerCode: customerCode.value,
      houseTypeCode: selectedValue,
    })

    // 4. Safely extract array or fall back to an empty list
    stepList.value = Array.isArray(response.data?.stepList) 
      ? response.data.stepList 
      : []

  } catch (error) {
    console.error('Failed to fetch house type data:', error)
    stepList.value = [] // Guard against corrupted or partial state on error
  } finally {
    // 5. Always release screen block
    overlay.value = false
  }
}
</script>

<style>
/* Global reset just for this example setup */
body {
  margin: 0;
  font-family: Arial, sans-serif;
  background-color: #f4f4f4;
}

.content {
  margin-top: 80px; /* Keeps content from hiding behind the fixed header */
  padding: 2rem;
  text-align: center;
}
</style>