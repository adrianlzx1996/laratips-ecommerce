<?php

    namespace App\Http\Requests;

    use App\Models\Category;
    use App\Models\Product;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Validation\Rule;

    class StoreProductsRequest extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize ()
        {
            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, mixed>
         */
        public function rules ()
        {
            $model = $this->route('product');

            return [
                'name'           => [ 'bail', 'required', 'string', 'max:255' ],
                'description'    => [ 'bail', 'nullable', 'string' ],
                'slug'           => [ 'bail', 'required', 'string', 'max:255', Rule::unique(Product::class)->ignore($model->id ?? null) ],
                'cost_price'     => [ 'bail', 'required', 'integer' ],
                'price'          => [ 'bail', 'required', 'integer' ],
                'active'         => [ 'bail', 'boolean' ],
                'featured'       => [ 'bail', 'boolean' ],
                'show_on_slider' => [ 'bail', 'boolean' ],
                'category_id'    => [ 'bail', 'required', Rule::exists(Category::class)->whereNull('parent_id') ],
                'subcategory_id' => [ 'bail', 'required', Rule::exists(Category::class)->whereNotNull('parent_id') ],
            ];
        }

        public function saveData ()
        {
            $data = $this->safe()->only([ 'name', 'slug', 'description', 'price', 'featured', 'active', 'show_on_slider', 'cost_price', ]);

            return $data;
        }

        public function categoryIds ()
        : Collection
        {
            return collect([ $this->category_id, $this->subcategory_id ])->filter()->values();
        }
    }
