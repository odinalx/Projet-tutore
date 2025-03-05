import { z } from 'zod'

// Types pour la configuration des champs
export interface FieldConfig {
  name: string
  label: string
  type: 'text' | 'email' | 'number' | 'date' | 'select' | 'checkbox' | 'tel' | 'textarea'
  required?: boolean
  options?: { label: string; value: string | number }[] // Pour les champs select
  validation?: {
    min?: number
    max?: number
    pattern?: string
    message?: string
  }
}

// Type pour la configuration complète du formulaire
export interface FormConfig {
  id: string
  title: string
  fields: FieldConfig[]
}

// Fonction pour générer un schéma Zod à partir de la configuration
export function generateZodSchema(config: FormConfig) {
  const schemaFields: Record<string, z.ZodTypeAny> = {}

  config.fields.forEach((field) => {
    // Création du schéma de base selon le type
    switch (field.type) {
      case 'email': {
        const fieldSchema = z.string().email(field.validation?.message || 'Email invalide')
        schemaFields[field.name] = field.required ? fieldSchema : fieldSchema.optional()
        break
      }
      case 'number': {
        let fieldSchema = z.number()
        if (field.validation?.min !== undefined) {
          fieldSchema = fieldSchema.gte(field.validation.min)
        }
        if (field.validation?.max !== undefined) {
          fieldSchema = fieldSchema.lte(field.validation.max)
        }
        schemaFields[field.name] = field.required ? fieldSchema : fieldSchema.optional()
        break
      }
      case 'date': {
        const fieldSchema = z
          .string()
          .regex(/^\d{4}-\d{2}-\d{2}$/, 'Format de date invalide (YYYY-MM-DD)')
        schemaFields[field.name] = field.required ? fieldSchema : fieldSchema.optional()
        break
      }
      case 'select': {
        let fieldSchema: z.ZodType<string | number>
        if (field.options) {
          const values = field.options.map((opt) => opt.value)
          fieldSchema = z.union(
            values.map((v) => z.literal(v)) as [
              z.ZodLiteral<string | number>,
              z.ZodLiteral<string | number>,
              ...z.ZodLiteral<string | number>[],
            ]
          )
        } else {
          fieldSchema = z.string()
        }
        schemaFields[field.name] = field.required ? fieldSchema : fieldSchema.optional()
        break
      }
      case 'checkbox': {
        const fieldSchema = z.boolean()
        schemaFields[field.name] = field.required ? fieldSchema : fieldSchema.optional()
        break
      }
      case 'tel': {
        const fieldSchema = z
          .string()
          .regex(/^(\+\d{1,3}[- ]?)?\d{10}$/, 'Numéro de téléphone invalide')
        schemaFields[field.name] = field.required ? fieldSchema : fieldSchema.optional()
        break
      }
      case 'textarea':
      case 'text': {
        let fieldSchema = z.string()
        if (field.validation?.min !== undefined) {
          fieldSchema = fieldSchema.min(field.validation.min)
        }
        if (field.validation?.max !== undefined) {
          fieldSchema = fieldSchema.max(field.validation.max)
        }
        schemaFields[field.name] = field.required ? fieldSchema : fieldSchema.optional()
        break
      }
      default: {
        let fieldSchema = z.string()
        if (field.validation?.pattern) {
          fieldSchema = fieldSchema.regex(
            new RegExp(field.validation.pattern),
            field.validation.message
          )
        }
        schemaFields[field.name] = field.required ? fieldSchema : fieldSchema.optional()
      }
    }
  })

  return z.object(schemaFields)
}

// Type pour les valeurs du formulaire
export type FormValues = Record<string, string | number | boolean>

// Exemple d'utilisation :
/*
const formConfig: FormConfig = {
  id: "1",
  title: "Inscription",
  fields: [
    {
      name: "email",
      label: "Email",
      type: "email",
      required: true
    },
    {
      name: "age",
      label: "Âge",
      type: "number",
      required: true,
      validation: {
        min: 18,
        max: 100,
        message: "L'âge doit être entre 18 et 100 ans"
      }
    }
  ]
}

const schema = generateZodSchema(formConfig)
*/
