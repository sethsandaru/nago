# Solution

Thanks for checking my solution. To run my solution, you only need `Node`, ideally 16+.

Since our first priority is time, so there would be consequences, a lot of space will be used during the time the script running.

## Note
- I don't build a cool CLI stuff, you can find change the params of each test case in the `solution.js`
- The records will be generated concurrently utilizing the Event Loop.
  - Would be faster than the traditional `for i = 0; i < 50M`
- I don't use any storage/DB, everything will be stored in-memory (runtime).

## Run the solution

```bash
node --max-old-space-size=20000 solution.js
```

## My benchmark

I'm using GH CodeSpaces with this spec:

- CPU: 16-core
- RAM: 32GB

Overall logs:

```text
Building 50M records... Will take a little while.
Total Jobs to process concurrently: 5000
Generation complete.
Generate 50M records took: 54.358s

==
Find by Timestamp (ok and null) took: 0s
==
Find by user (ok and null) took: 0.001s
==
Calculate total points of 200 users took: 0.545s
==
Calculate total points between 2 dates took: 176.859s
```
