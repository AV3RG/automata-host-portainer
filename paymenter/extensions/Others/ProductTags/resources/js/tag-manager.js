/**
 * Product Tags JavaScript Helper
 * Provides client-side functionality for tag management
 */

class ProductTagManager {
    constructor(options = {}) {
        this.baseUrl = options.baseUrl || '/api/tags';
        this.csrfToken = options.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.cache = new Map();
        this.cacheTimeout = options.cacheTimeout || 300000; // 5 minutes
    }

    /**
     * Get all tags with optional search
     */
    async getTags(search = '', limit = 50) {
        const cacheKey = `tags_${search}_${limit}`;
        
        if (this.cache.has(cacheKey)) {
            const cached = this.cache.get(cacheKey);
            if (Date.now() - cached.timestamp < this.cacheTimeout) {
                return cached.data;
            }
        }

        try {
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (limit) params.append('limit', limit);

            const response = await fetch(`${this.baseUrl}?${params}`);
            const data = await response.json();

            this.cache.set(cacheKey, {
                data: data,
                timestamp: Date.now()
            });

            return data;
        } catch (error) {
            console.error('Error fetching tags:', error);
            return { tags: [] };
        }
    }

    /**
     * Get popular tags
     */
    async getPopularTags(limit = 10) {
        const cacheKey = `popular_${limit}`;
        
        if (this.cache.has(cacheKey)) {
            const cached = this.cache.get(cacheKey);
            if (Date.now() - cached.timestamp < this.cacheTimeout) {
                return cached.data;
            }
        }

        try {
            const response = await fetch(`${this.baseUrl}/popular?limit=${limit}`);
            const data = await response.json();

            this.cache.set(cacheKey, {
                data: data,
                timestamp: Date.now()
            });

            return data;
        } catch (error) {
            console.error('Error fetching popular tags:', error);
            return { tags: [] };
        }
    }

    /**
     * Get tags for a specific product
     */
    async getProductTags(productId) {
        const cacheKey = `product_${productId}`;
        
        if (this.cache.has(cacheKey)) {
            const cached = this.cache.get(cacheKey);
            if (Date.now() - cached.timestamp < this.cacheTimeout) {
                return cached.data;
            }
        }

        try {
            const response = await fetch(`${this.baseUrl}/product?product_id=${productId}`);
            const data = await response.json();

            this.cache.set(cacheKey, {
                data: data,
                timestamp: Date.now()
            });

            return data;
        } catch (error) {
            console.error('Error fetching product tags:', error);
            return { tags: [] };
        }
    }

    /**
     * Create a new tag
     */
    async createTag(tagData) {
        try {
            const response = await fetch(this.baseUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify(tagData)
            });

            const data = await response.json();

            if (response.ok) {
                // Clear relevant cache entries
                this.clearCache('tags_');
                this.clearCache('popular_');
            }

            return data;
        } catch (error) {
            console.error('Error creating tag:', error);
            return { success: false, error: 'Network error' };
        }
    }

    /**
     * Assign tags to a product
     */
    async assignTagsToProduct(productId, tagIds) {
        try {
            const response = await fetch(`${this.baseUrl}/assign`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify({
                    product_id: productId,
                    tag_ids: tagIds
                })
            });

            const data = await response.json();

            if (response.ok) {
                // Clear product cache
                this.clearCache(`product_${productId}`);
                this.clearCache('popular_');
            }

            return data;
        } catch (error) {
            console.error('Error assigning tags:', error);
            return { success: false, error: 'Network error' };
        }
    }

    /**
     * Clear cache entries matching a pattern
     */
    clearCache(pattern) {
        for (const key of this.cache.keys()) {
            if (key.includes(pattern)) {
                this.cache.delete(key);
            }
        }
    }

    /**
     * Clear all cache
     */
    clearAllCache() {
        this.cache.clear();
    }

    /**
     * Create a tag input component
     */
    createTagInput(container, options = {}) {
        const config = {
            placeholder: 'Search or create tags...',
            maxTags: 0,
            allowCreate: true,
            showPopular: true,
            productId: null,
            ...options
        };

        const wrapper = document.createElement('div');
        wrapper.className = 'tag-input-wrapper';
        
        const input = document.createElement('input');
        input.type = 'text';
        input.placeholder = config.placeholder;
        input.className = 'tag-input form-control';
        
        const dropdown = document.createElement('div');
        dropdown.className = 'tag-dropdown hidden';
        
        const selectedTags = document.createElement('div');
        selectedTags.className = 'selected-tags';
        
        wrapper.appendChild(selectedTags);
        wrapper.appendChild(input);
        wrapper.appendChild(dropdown);
        container.appendChild(wrapper);

        let selectedTagIds = new Set();
        let debounceTimer;

        // Load existing tags if productId is provided
        if (config.productId) {
            this.getProductTags(config.productId).then(data => {
                data.tags.forEach(tag => {
                    addSelectedTag(tag);
                });
            });
        }

        // Input event handler
        input.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const query = e.target.value.trim();
                if (query.length > 0) {
                    showDropdown(query);
                } else {
                    hideDropdown();
                }
            }, 300);
        });

        // Focus event handler
        input.addEventListener('focus', () => {
            if (config.showPopular && input.value === '') {
                showPopularTags();
            }
        });

        // Click outside to close dropdown
        document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) {
                hideDropdown();
            }
        });

        async function showDropdown(query) {
            const data = await this.getTags(query, 10);
            displayTags(data.tags, query);
            dropdown.classList.remove('hidden');
        }

        async function showPopularTags() {
            const data = await this.getPopularTags(10);
            displayTags(data.tags, '', 'Popular tags');
            dropdown.classList.remove('hidden');
        }

        function displayTags(tags, query, title = '') {
            dropdown.innerHTML = '';

            if (title) {
                const titleEl = document.createElement('div');
                titleEl.className = 'tag-dropdown-title';
                titleEl.textContent = title;
                dropdown.appendChild(titleEl);
            }

            tags.forEach(tag => {
                if (selectedTagIds.has(tag.id)) return;

                const item = document.createElement('div');
                item.className = 'tag-dropdown-item';
                item.innerHTML = `
                    <span class="tag-preview" style="background-color: ${tag.color}20; color: ${tag.color};">
                        ${tag.name}
                    </span>
                    <span class="tag-usage">${tag.usage_count} products</span>
                `;
                
                item.addEventListener('click', () => {
                    addSelectedTag(tag);
                    input.value = '';
                    hideDropdown();
                });

                dropdown.appendChild(item);
            });

            // Add create option if query doesn't match existing tags and allowCreate is true
            if (query && config.allowCreate && !tags.some(tag => tag.name.toLowerCase() === query.toLowerCase())) {
                const createItem = document.createElement('div');
                createItem.className = 'tag-dropdown-item tag-create';
                createItem.innerHTML = `<span>Create "${query}"</span>`;
                
                createItem.addEventListener('click', async () => {
                    const newTag = await this.createTag({ name: query });
                    if (newTag.success) {
                        addSelectedTag(newTag.tag);
                        input.value = '';
                        hideDropdown();
                    }
                });

                dropdown.appendChild(createItem);
            }

            if (dropdown.children.length === 0) {
                const noResults = document.createElement('div');
                noResults.className = 'tag-dropdown-empty';
                noResults.textContent = 'No tags found';
                dropdown.appendChild(noResults);
            }
        }

        function addSelectedTag(tag) {
            if (selectedTagIds.has(tag.id)) return;
            if (config.maxTags > 0 && selectedTagIds.size >= config.maxTags) return;

            selectedTagIds.add(tag.id);

            const tagEl = document.createElement('span');
            tagEl.className = 'selected-tag';
            tagEl.style.backgroundColor = tag.color;
            tagEl.style.color = getContrastColor(tag.color);
            tagEl.innerHTML = `
                ${tag.name}
                <button type="button" class="tag-remove" data-tag-id="${tag.id}">×</button>
            `;

            tagEl.querySelector('.tag-remove').addEventListener('click', () => {
                selectedTagIds.delete(tag.id);
                tagEl.remove();
                triggerChange();
            });

            selectedTags.appendChild(tagEl);
            triggerChange();
        }

        function hideDropdown() {
            dropdown.classList.add('hidden');
        }

        function triggerChange() {
            const event = new CustomEvent('tagsChanged', {
                detail: {
                    tagIds: Array.from(selectedTagIds),
                    tags: Array.from(selectedTagIds)
                }
            });
            wrapper.dispatchEvent(event);
        }

        function getContrastColor(hexColor) {
            const r = parseInt(hexColor.substr(1, 2), 16);
            const g = parseInt(hexColor.substr(3, 2), 16);
            const b = parseInt(hexColor.substr(5, 2), 16);
            const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
            return luminance > 0.5 ? '#000000' : '#ffffff';
        }

        return {
            getSelectedTags: () => Array.from(selectedTagIds),
            setSelectedTags: (tagIds) => {
                selectedTagIds.clear();
                selectedTags.innerHTML = '';
                tagIds.forEach(async (tagId) => {
                    // This would need to fetch tag data by ID
                    // Implementation depends on your specific needs
                });
            }
        };
    }
}

// Initialize global instance
window.ProductTagManager = ProductTagManager;

// Auto-initialize if container exists
document.addEventListener('DOMContentLoaded', () => {
    const containers = document.querySelectorAll('[data-tag-input]');
    containers.forEach(container => {
        const options = JSON.parse(container.dataset.tagInput || '{}');
        const manager = new ProductTagManager();
        manager.createTagInput(container, options);
    });
});
