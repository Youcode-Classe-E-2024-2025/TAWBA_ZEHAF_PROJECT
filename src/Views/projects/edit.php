<?php ob_start(); ?>
<script src="https://cdn.tailwindcss.com"></script>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate mb-8">
            Edit Project
        </h2>

        <form action="/projects/edit/<?php echo $project['id']; ?>" method="post"
            class="space-y-8 divide-y divide-gray-200">
            <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                <div>
                    <div class="mt-6 sm:mt-5 space-y-6 sm:space-y-5">
                        <div
                            class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="name" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                Project Name
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <input type="text" name="name" id="name"
                                    value="<?php echo htmlspecialchars($project['name']); ?>" required
                                    class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div
                            class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="description" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                Description
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <textarea id="description" name="description" rows="3"
                                    class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"><?php echo htmlspecialchars($project['description']); ?></textarea>
                            </div>
                        </div>

                        <div
                            class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="is_public" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                Public Project
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <input type="checkbox" name="is_public" id="is_public"
                                    <?php echo $project['is_public'] ? 'checked' : ''; ?>
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-5">
                <div class="flex justify-end">
                    <button type="submit"
                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Project
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require 'src/Views/layout.php'; ?>