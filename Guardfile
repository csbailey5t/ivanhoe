# A sample Guardfile
# More info at https://github.com/guard/guard#readme

guard :rspec do
  watch(%r{^spec/.+_spec\.rb$})
  watch(%r{^(.+)\.php$})
  watch('spec/spec_helper.rb')  { "spec" }
end

