# FAQ Extension for Paymenter

This extension provides a comprehensive FAQ management system for Paymenter that integrates with your existing product categories. Instead of creating new FAQ categories, you can add FAQ questions directly to the product categories you already have.

## Features

- **Product Category Integration**: Works with existing Paymenter product categories
- **FAQ Questions**: Create detailed Q&A pairs with rich text answers for each category
- **Admin Management**: Full CRUD operations through Filament admin panel
- **Sorting & Organization**: Drag-and-drop reordering for questions within categories
- **Status Management**: Active/inactive status for questions
- **Featured Questions**: Mark important questions as featured
- **No Duplication**: Leverages existing category structure

## Installation

1. Place the extension in the `extensions/Others/FAQ/` directory
2. Enable the extension through the Paymenter admin panel
3. The extension will automatically run migrations to create necessary database tables

## Database Structure

### FAQ Questions Table (`ext_faq_questions`)
- `id` - Primary key
- `product_category_id` - Foreign key to existing product categories table
- `question` - The question text
- `answer` - Rich text answer
- `sort_order` - Display order within category
- `is_active` - Active status
- `is_featured` - Featured question flag
- `timestamps` - Created/updated timestamps

## How It Works

The extension integrates seamlessly with your existing product category system:

1. **Existing Categories**: Uses the product categories you already have configured
2. **FAQ Questions**: Add questions directly to these categories
3. **Automatic Organization**: Questions are automatically organized by product category
4. **No New Categories**: No need to create or manage separate FAQ categories

## Admin Panel

The extension adds one new resource to the Filament admin panel:

**FAQ Questions** (`/admin/faq-questions`)
- Create, edit, and delete FAQ questions
- Select from existing product categories
- Rich text editor for answers
- Mark questions as featured
- Drag-and-drop reordering
- Active/inactive status management

## Usage

### Adding FAQ Questions
1. Navigate to FAQ Questions in the admin panel
2. Click "Create" to add a new question
3. Select a product category from the dropdown (uses existing categories)
4. Enter the question and answer
5. Set the sort order and status
6. Optionally mark as featured
7. Save the question

### Managing Questions
- Questions are automatically organized by product category
- Use the sort order fields to control display order within each category
- Drag and drop rows in the admin tables for quick reordering
- Use the active status to temporarily hide questions without deleting
- Filter questions by product category, active status, or featured status

### Category Integration
- Each question is linked to an existing product category
- The relationship is maintained through the `product_category_id` field
- Questions inherit the category's organization and structure
- No duplicate category management required

## Benefits

- **Seamless Integration**: Works with your existing product structure
- **No Duplication**: Leverages categories you already have
- **Easy Management**: Single interface for FAQ questions
- **Consistent Organization**: Questions follow your existing category hierarchy
- **Future-Ready**: Easy to extend for frontend display

## Future Enhancements

This extension is designed to be easily extensible for future features such as:
- Frontend display components on category/product pages
- Search functionality across all FAQ questions
- User-submitted questions
- Analytics and reporting
- Multi-language support

## Support

For support or feature requests, please refer to the Paymenter documentation or contact the development team.

