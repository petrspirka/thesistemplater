<template>
	<!--
    SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
    SPDX-License-Identifier: AGPL-3.0-or-later
    -->
	<div class="settings-thesistemplater">
		<NcSettingsSection :title="t('thesistemplater', 'ThesisTemplater Settings')"
			:description="t('thesistemplater', 'This page offers multiple settings to change how ThesisTemplater behaves.')" />
		<NcSettingsSection :title="t('thesistemplater', 'CSV Parsing settings')"
			:description="t('thesistemplater', 'This section controls how the CSV files from IS/STAG are processed.')">
			<form v-if="loaded" class="settings-thesistemplater-form" @submit.prevent="submit">
				<InputField :id="firstNameField" :value.sync="form.firstNameField" :text="t('thesistemplater', 'Name of the column containing first name')" />
				<InputField :id="lastNameField" :value.sync="form.lastNameField" :text="t('thesistemplater', 'Name of the column containing last name')" />
				<InputField :id="emailField" :value.sync="form.emailField" :text="t('thesistemplater', 'Name of the column containing email')" />
				<InputField :id="typeField" :value.sync="form.typeField" :text="t('thesistemplater', 'Name of the column containing type of thesis')" />
				<InputField :id="studentField" :value.sync="form.studentDirectory" :text="t('thesistemplater', 'Path to the directory where the student directories should be created')" />
				<NcButton native-type="submit">
					{{ t('thesistemplater', 'Submit') }}
				</NcButton>
			</form>
			<div v-else>
				<NcLoadingIcon />
			</div>
		</NcSettingsSection>
	</div>
</template>

<script>
import { NcSettingsSection, NcButton, NcLoadingIcon } from '@nextcloud/vue'
import InputField from '../Components/InputField.vue'

import '@nextcloud/dialogs/style.css'
import { generateUrl } from '@nextcloud/router'
import { showError, showSuccess } from '@nextcloud/dialogs'
import axios from '@nextcloud/axios'

export default {
	name: 'Settings',
	components: {
		NcSettingsSection,
		NcButton,
		NcLoadingIcon,
		InputField,
	},
	data() {
		return {
			form: {
				typeField: '',
				firstNameField: '',
				lastNameField: '',
				emailField: '',
				studentDirectory: '',
			},
			prev: {},
			loaded: false,
		}
	},
	/**
	 * Fetch list of notes when the component is loaded
	 */
	async mounted() {
		try {
			const response = await axios.get(generateUrl('/apps/thesistemplater/settings'))
			// Manual update of form due to the nature of setConfigValues
			this.form = response.data
			this.setConfigValues(response.data)
			this.loaded = true
		} catch (e) {
			console.error(e)
			showError(t('thesistemplater', 'Could not fetch settings'))
		}
	},

	methods: {
		setConfigValues(data) {
			// Cloning the form object object. Unlike Object.assign, this will create a deep copy
			this.prev = JSON.parse(JSON.stringify(this.form))
			this.form = data
		},
		async submit() {
			const formData = {}
			let changed = false
			Object.keys(this.form).forEach(key => {
				if (this.form[key] !== this.prev[key]) {
					formData[key] = this.form[key]
					changed = true
				}
			})
			if (!changed) {
				showSuccess(t('thesistemplater', 'No changes to save'))
				return
			}
			try {
				await axios.post(generateUrl('/apps/thesistemplater/settings'), { data: this.form })
				showSuccess(t('thesistemplater', 'Settings saved'))
				this.prev = this.form
			} catch (e) {
				console.error(e)
				showError(t('thesistemplater', 'Failed to save settings'))
			}
		},
	},
}
</script>
<style scoped>
	#app-content > div {
		width: 100%;
		height: 100%;
		display: flex;
		flex-direction: column;
		flex-grow: 1;
	}

	input[type='text'] {
		width: 100%;
	}

	textarea {
		flex-grow: 1;
		width: 100%;
	}

	.settings-thesistemplater {
		width: 100%;
		height: 100%;
	}

	.settings-thesistemplater-form > * {
		margin: 8px;
	}
</style>
