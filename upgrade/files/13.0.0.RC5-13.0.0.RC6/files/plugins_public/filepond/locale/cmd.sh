# change files Linux
# find ./ -type f -exec sed -i 's/glFilepondLocale =/glFilepondLocale =/' {} \;

# change files Mac OSX
# find ./ -type f -exec sed -i '' -e 's/glFilepondLocale =/glFilepondLocale =/' {} \;

# get list of langs
# ls -la | grep "\.js$" | awk '{print $9}' | awk -F '.' '{printf("\047%s\047 => 1, ", $1); }'
